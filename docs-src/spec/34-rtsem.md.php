<div class="pagebreak"></div>

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
in <?= $langname ?> should not statically link with the <?= $langname ?>
runtime. Unless no opaque objects is passed between translation units compiled
by different implementations (which is unlikely), statically linking to
different incompatible implementations of the runtime may result in undefined
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

<?= hc_H2("Member Access") ?>

-- Note: much of this section is scrubbed. --

For the purpose of this section, the special value `null` is implemented as if
it has same type as a full object, and a value proper of 0.
-- TODO (2025-08-13): Should I retain this paragraph? If so, how and where? --

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
}

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

    // There are `n_entries + 1` elements,
    // last of which `type` being `NULL`.
    struct value_nativeobj static_members[];
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

Finally, non-FFI functions may receive their arguments differently than FFI
functions. For example, a non-FFI function may receive an array of values -
for this purpose, the `ref` type may be represented inside a `val` using the
type code enumeration `valtyp_ref`.

<?= hc_H2("Finalization and Garbage Collection") ?>

```
void cxing_finalize(struct value_nativeobj);
void cxing_gc(void);
```
