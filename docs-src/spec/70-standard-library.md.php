<div class="pagebreak"></div>

<?= hc_H1("Standard Library") ?>

In the following sections, special notations that're not part of the langauge
are used for ease of presentation.

**The meaning of such notation**:

```
[ffi] {method,subr} [<type>] identifier(args);
```

*is as follow*:

The bracketed `[ffi]` means this is a method or a subroutine can be either FFI
or non-FFI. When it's FFI, it's return type is `<type>`.

**The meaning of such notation**:

```
<name1>(<name2>) := { ... }
```

*is as follow*:

The entity identified by `<name1>` is a subclass of `<name2>` (typically `val`),
and consist of additional members enumerated by the ellipsis `...`.
The word "subclass" is used here only to imply that object of type `<name1>`
may be used anywhere `<name2>` is expected. `<name2>` is not optional, because
it signifies to implementors of the runtime how an argument of such type
are to be passed.

**Note**: The notation is inspired by Python. Object-oriented programming is
not a supported paradigm of <?= langname() ?>. The notation is strictly for
presentation, and does not correspond to any existing language feature.

Because <?= langname() ?> is a dynamically typed language, typing is
not enforced, and the implementation does not diagnose typing errors (because
there aren't any). Checking the characteristics of an object is entirely
the responsibility of codes that use it.
