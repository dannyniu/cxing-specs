<?= hc_H1("Library for the String Data Type") ?>

```
str(val) := {
  [ffi] method [long] len(),
  [ffi] method [str] trunc(ulong newlength),
  [ffi] method [str] putc(long c),
  [ffi] method [str] puts(str s),
  [ffi] method [str] putfin(),
  [ffi] method [long] cmpwith(str s2), // efficient byte-wise collation.
  [ffi] method [bool] equals(str s2), // constant-time, cryptography-safe.
  [ffi] method [structureddata] map(val structlayout),
};

structureddata(val) := {
  [ffi] method [val] unmap(),
}
```

The string type `str` is a sequence of bytes.

A string has a *length* that's reported by the `len()` function,
and can be altered using the `trunc()` function.

The `putc()` function can be used to append a byte whose integer value is
specified by `c`, to the end of the string; the `puts()` function can be
used to append another string to the end; both `putc()` and `puts()` may
buffer the input on the working context of the string, such buffer need to be
flushed using the `putfin()` function before the string is used in other
places.

For `trunc()`, `putc()`, `puts()`, and `putfin()`, the object itself is
returned on success, and `null` is returned on failure.

The `cmpwith()` returns less than, equal to, or greater than 0 if the string is
less than, the same as, or greater than `s2`. The strict prefix of a string is
less than the string to which it's a prefix of.

The `equals()` function returns `true` if the string equals `s2` and false
otherwise. If the 2 strings are of the same length, it is guaranteed that
the comparison is done without cryptographically exploitable time side-channel.

The `map()` function creates an object that is a parsed representation of the
underlying data structure. This object can be used to modify the memory backing
of the data structure if the corresponding memory backing is writable. The
memory backing is writable by default, and the circumstances under which it's
not is implementation-defined.

The `unmap()` function unmaps the parsed representation, thus making it
no longer usable. The variable can then only be finalized (or overwritten,
which would imply a finalization). The `trunc()` function cannot be called on
the string unless there's no active mapping of the string.
