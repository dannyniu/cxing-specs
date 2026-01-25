<?= hc_H1("Library for the String Data Type") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

```
str(obj) := {
  method len(),
  method trunc(newlength),
  method putc(c),
  method puts(s),
  method putfin(),
  method cmpwith(s2), // efficient byte-wise collation.
  method equals(s2), // constant-time, cryptography-safe.
  [method map(structlayout)] := {
    method __get__(k),
    method __set__(k, v),
    method unmap(),
  },
};
```

The string type `str` is a sequence of bytes. Some APIs may expect nul-terminated
strings, and would ignore any byte after the first nul byte.

A string has a *length* that's reported by the `len()` function as a `long`,
and can be altered using the `trunc()` function.

The `putc()` function can be used to append a byte whose _integer_ value is
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

The `equals()` function returns `true` if the string equals `s2` and `false`
otherwise. If the 2 strings are of the same length, it is guaranteed that
the comparison is done without cryptographically exploitable time side-channel.

The `map()` function creates an object that is a parsed representation of the
underlying data structure. This object can be used to modify the memory backing
of the data structure if the corresponding memory backing is writable. The
memory backing is writable by default, and the circumstances under which it's
not writable is implementation-defined.

The `unmap()` function unmaps the parsed representation, thus making it
no longer usable, and returns `true`. The variable can then only be finalized
(or overwritten, which would imply a finalization). The `trunc()` function
cannot be called on the string unless there's no active mapping of the string.

**Note**: Previously, the `unmap()` function returned `null`. Because nullish
values are reserved in <?= langname() ?> entirely as an error indicator, its
return type is now changed to `bool`.

**Note**: Although the canonical way to access data behind a `str` object, is
to first map it to a structure type, it is anticipated that a common extension
will exist in the wild allowing for "mutable" strings - where they implement
the `__get__` and the `__set__` methods. This is not yet considered for
standardization eventhough there's no compelling reason not to. For
implementations that do provide this extension, the following requirements apply:

> 1. The `__get__` method shall return `long` for byte range 0-255 inclusive,
>    and -1 on out of bound access.
> 2. The `__set__` method shall accept second argument of at least the `long` and
>    the `ulong` type, and shall cast `double` to `ulong` by truncating fractions.
>    The byte values shall be set by discarding all but lowest 8 bits of the byte
>    (non-octet bytes are not considered for <?= langname() ?>).
> 3. The application shall ensure the key be non-negative integer indicies of
>    type `long` or `ulong`, and the implementation may have undefined behavior
>    if this requirement on the applications are not met.
