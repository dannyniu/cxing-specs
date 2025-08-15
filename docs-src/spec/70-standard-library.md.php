<div class="pagebreak"></div>

<?= hc_H1("Standard Library") ?>

In the following sections, special notations that're not part of the langauge
are used for ease of presentation.

The meaning of such declaration:

```
[ffi] {method,subr} [<type>] identifier(args);
```

is as follow:

The bracketed `[ffi]` means this is a method or a subroutine can be either FFI
or non-FFI. When it's FFI, it's return type is `<type>`.

---

The meaning of such declaration:

```
<name> := { ... }
```

is as follow:

The entity identified by `<name>` is an object consisting of properties
enumerated by the ellipsis `...`.
