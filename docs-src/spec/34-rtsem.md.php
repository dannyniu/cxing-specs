<?= hc_H1("Runtime Semantics") ?>

While the features and the specification of the language is supposed to be
stable, **as a guiding policy**, in the unlikely event where certain interface
in the runtime posing efficiency problem are to be replaced with alternatives,
deprecation periods are given in the current major version of the runtime
(and thus the language), before removal in a future major version should that
happen; in the even more unlikely event where certain interface exposes a
vulnerability so fundamental that necessitates its removal, the language
along with its runtime is revised, a new version is released, and the
vulnerable version is deprecated immediately. The versioning practice is
in line with recommendation by [Semantic Versioning](https://semver.org/).

<?= hc_H2("Binary Linking Compatibility") ?>

Dynamic libraries and applications linking with dynamic libraries programmed
in <?= langname() ?> should not statically link with the <?= langname()." " ?>
runtime. Unless no opaque objects is passed between translation units compiled
by different implementations (which is unlikely), statically linking to
different incompatible implementations of the runtime may result in undefined
behavior when opaque objects and the functions that manipulates them are from
different implementations.

The version of the runtime and the version of the language specification are
coupled together to make it easy to determine which version of runtime should
be used to obtain the features of relevant version of the language. If the
standard library is to be provided, then the runtime should be provided as part
of the standard library, the name of the linking library file should be the
same for both the runtime and for when it's extended into/as standard library.

<?php
 if( !isset($spec_semver) )
 {
   global $spec_majver, $spec_minver, $spec_revver, $spec_semver;
 }
?>

The recommended name for the library corresponding to version
<?= "$spec_majver.$spec_minver" ?> of the specification is
`libcxing<?= "$spec_majver.so.$spec_minver" ?>` for systems using the
UNIX System V ABI such as Linux, BSDs, and several commercial Unix distros.
For the Darwin family of operating systems such as macOS, iOS, etc. the
recommended name is `libcxing<?= "$spec_majver.$spec_minver.dylib" ?>` .

For some platforms such as Windows, vendors have greater control over the
dynamic libraries bundled with the programs in an application. Therefore
no particular recommendations are made for these platforms.

<?= hc_H2("Calling Conventions and Foreign Function Interface") ?>

The types `long` and `ulong` are passed to functions as C types `int64_t`
and `uint64_t` respectively; the type `double` is passed as the
C type `double`; handles to full objects and opaque objects are passed as
C language object pointers.

The "value" and "lvalue" native object are defined as the
following C structure types:

```
enum types_enum : uint64_t {
    valtyp_null = 0,
    valtyp_long,
    valtyp_ulong,
    valtyp_double,

    // the opaque object type.
    valtyp_obj,

    // `porper.p` points to a `struct value_nativeobj`.
    valtyp_ref,

    // FFI and non-FFI subroutines and methods.
    valtyp_subr = 6,
    valtyp_method,
    valtyp_ffisubr,
    valtyp_ffimethod,

    // 10 types so far.
};

struct value_nativeobj;
struct type_nativeobj;

struct value_nativeobj {
    union { double f; uint64_t l; int64_t u; void *p; } proper;
    union {
        const struct type_nativeobj *type;
        uint64_t pad; // zero-extend the type pointer to 64-bit on ILP32 ABIs.
    };
};

struct lvalue_nativeobj {
    struct value_nativeobj value;

    // The following fields are for lvalues:
    void *scope;
    void *key;
};

struct type_nativeobj {
    enum types_enum typeid;
    uint64_t n_entries;

    // There are `n_entries + 1` elements, last of which `type` being the only
    // `NULL` entry in the array.
    struct {
        const char *name;
        struct value_nativeobj *member;
    } static_members[];
};
```

For the special value `null`, there are 2 accepted representations that
implementations MUST anticipate:
- `typeid` having an enumeration value of 0 - `valtyp_null`.
- `value.p.proper` having `NULL` with `typeid` having `valtyp_obj`.

For non-FFI functions, parameters declared with type `val` receive arguments as
the `struct value_nativeobj` structure in runtime binding; values are returned
in similarly in the `struct value_nativeobj` structure type.
(As mentioned in <?= hcNamedSection("Subroutines and Methods") ?>,
no function may return a `ref`.)

For FFI functions, parameters declared with type `long`, `ulong`, and `double`
receive arguments as their respective C language type, and in accordance to the
ABI specification of relevant platform(s); values are returned according to
their type declaration also in accordance to relevant platform ABI definitions.

For both non-FFI and FFI functions, parameters declared as `ref` receive
arguments as the `struct value_nativeobj *` pointer type in runtime binding.

Methods receive `this` as their first argument as the `ref` language type (i.e.
the `struct value_nativeobj *` runtime pointer type).

<?= hc_H2("Finalization and Garbage Collection") ?>

Resources are generically defined as what enables a program to run and
function, and assciated with it. When a value is destroyed, the resources
associated with it are finalized and released, which may lead to the resources
be free for reuse elsewhere.

**Note**: On a reference-counted implementation (which is conceptually
prescribed), releasing an object "decreases" its reference count, and when the
reference count reaches 0, the resources are "freed". Under
implementation-defined circumstances, an object may be released by all, but
still referenced somewhere (e.g. reference cycle), which require garbage
collection to fully "free" the object and its resources.

**Editorial Note**: Previously (before 2025-09-26), finalize and destroy were
used interchangeably; now finalize refer to that of resource and destroy refer
to that of values (i.e. the concept of value native objects).

```
ffi subr null cxing_gc();
```

The `cxing_gc` foreign function invokes the garbage collection process.

**Note**: In part because of the runtime implementation need to be informed of
destruction of values to finalize relevant resources, more pressingly because
of benefit to the design of idiomatic standard library features, copying and
destruction of values are now being defined. To define the concepts in terms of
reference counts would mean to depend on intrinsic implementation details, and
also that there's circular dependency in definition. Seeking an alternative,
it's discovered that copying and destroying are paired concepts that must be
described together, and this is the approach that will be taken right now.

To *copy* a value, means to preserve its existence in the event of
its *destruction*, which causes the value ceases to exist; when a value is
copied, the value and the copied value can both exist, and the destruction
of either don't affect the existence of the other.

The `__copy__` property is a method that copies its `this` argument and
returns "the copy". The `__final__` property is a method that releases the
resources used by the value before the destruction of the value.

The `__copy__` and `__final__` may not necessarily be type-associated
properties, programs can define their own types with copy and finalization
methods as long as the object they're implementing these methods for have
a `__set__` property.

**Note**: Primitive types such as `long`, `ulong`, and `double` may not need
a `__copy__` method - runtime recognizing these sort of types may copy them
in any way that may be assumed reasonable according to common sense. For types
without a `__final__` method, it is assumed that there are no resource consumed
by the value beyond what's already in the value native object structure.
