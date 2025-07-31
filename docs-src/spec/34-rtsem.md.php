<div class="pagebreak"></div>

Runtime Semantics
====

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

<a id="ffi">Foreign Function Interface</a>
----

The types `long` and `ulong` are passed to functions as C types `int64_t`
and `uint64_t` respectively; the type `double` is passed as the
C type `double`; handles to full objects and opaque objects are passed as
C language object pointers.

Binary Linking Compatibility
----

Dynamic libraries and applications linking with dynamic libraries programmed
in <?= $langname ?> should not statically link with the <?= $langname ?>
runtime. Unless full objects are not passed between translation units compiled
by different implementations (which is unlikely), statically linking to
different incompatible implementations of the runtime will result in undefined
behavior when members of full objects are accessed outside the translation
units where they were created.

The version of the runtime and the version of the language specification are
coupled together to make it easy to determine which version of runtime should
be used to obtain the features of relevant version of the language. If the
standard library is to be provided, then the runtime should be provided as part
of the standard library, the name of the linking library file should be the
same for both the runtime and for when it's extended into/as standard library.

The recommended name for the library corresponding to version
<?= "$spec_majver.$spec_minver" ?> of the specification is
`libcxing<?= "$spec_majver.so.$spec_minver" ?>` for systems using the
UNIX System V ABI such as Linux, BSDs, and several commercial Unix distros.
For the Darwin family of operating systems such as macOS, iOS, etc. the
recommended name is `libcxing<?= "$spec_majver.$spec_minver.dylib" ?>` .

For some platforms such as Windows, vendors have greater control over the
dynamic libraries bundled with the programs in an application. Therefore
no particular recommendations are made for these platforms.

Member Access
----

```
struct value_nativeobj cxing_get_named_member(
    fullobj_t *obj, const char *key);
struct value_nativeobj cxing_set_named_member(
    fullobj_t *obj, const char *key, struct value_nativeobj);

struct value_nativeobj cxing_get_indexed_member(
    fullobj_t *obj, long index);
struct value_nativeobj cxing_get_indexed_member(
    fullobj_t *obj, long index, struct value_nativeobj);
```

The `cxing_get_named_member` and the `cxing_get_indexed_member` functions
perform the "[read a key from an object](#obj-key-read)" algorithm.

The `cxing_set_named_member` and the `cxing_set_indexed_member` functions
perform the "[write a key onto an object](#obj-key-write)" algorithm.

For the member access operator `.` (`member` of `postfix` in
[Expressions](#expressions)), the `cxing_get_named_member` runtime function is
used.

For the indirect member access operator `[]` (`indirect` of `postfix` in
[Expressions](#expressions)), the `cxing_get_named_member` runtime function
is used if `expressions-list` evaluates to a string; the
`cxing_get_indexed_member` runtime function is used if it evaluates to the type
`long` or `ulong`. The behavior is UNSPECIFIED if `expressions-list` evaluates
to `double`.

For the purpose of this section, the special value `null` is implemented as if
it has same type as a full object, and a value proper of 0.

**Side Note** There was plan to support property setter and getter functions,
this plan was dropped because method members can do the equivalent, and
devising the feature would complicate the implementation of the language.

<a id="ffi-and-calls">Calling Conventions and Foreign Function Interface.</a>
----

The "value" and "lvalue" native object are defined as the
following C structure types:

```
enum value_types : uint64_t {
    valtyp_unspecified = 0,
    valtyp_long,
    valtyp_ulong,
    valtyp_double,
    valtyp_fullobj,
    valtyp_valref, // see below.
}

struct value_nativeobj {
    union { double f; uint64_t l; int64_t u; } proper;
    value_types type;
};

struct lvalue_nativeobj {
    struct value_nativeobj value;

    // The following fields are for lvalues:
    void *scope;
    void *key;
}
```

For non-FFI functions, parameters declared with type `val` receive arguments as
the `struct value_nativeobj` structure in runtime binding; values are returned
in similarly in the `struct value_nativeobj` structure type.

For FFI functions, parameters declared with type `long`, `ulong`, and `double`
receive arguments as their respective C language type, and in accordance to the
ABI specification of relevant platform(s); values are returned according to
their type declaration also in accordance to relevant platform ABI definitions.

For both non-FFI and FFI functions, parameters declared as `ref` receive
arguments as the `struct value_nativeobj *` pointer type in runtime binding.
Objects of other types are passed and returned as the `fullobj_t *` pointer type.

Methods receive `this` as their first argument as the `fullobj_t *` pointer
type; trait calls receive `this` as their first argument as the
`struct value_nativeobj *` pointer type.

Finally, non-FFI functions may receive their arguments differently than FFI
functions. For example, a non-FFI function may receive an array of values -
for this purpose, the `ref` type may be represented inside a `val` using the
type code enumeration `valtyp_ref`.

-- TODO: Should pointers be zero-extended on stack-based ILP32 platforms? Prior art: Vulkan. --

Finalization and Garbage Collection
----

```
void cxing_finalize(struct value_nativeobj);
void cxing_gc(void);
```
