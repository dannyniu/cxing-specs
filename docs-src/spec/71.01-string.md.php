Library for the String Data Type
====

```
str := {
  [ffi] method [long] len(),
  [ffi] method [long] trunc(long newlength),
  [ffi] method [long] putc(long c),
  [ffi] method [long] puts(str s),
  [ffi] method [long] putfin(),
  [ffi] method [long] cmpwith(str s2), // efficient byte-wise collation.
  [ffi] method [bool] equals(str s2), // constant-type, cryptography-safe.
  [ffi] method [structureddata] map(T structlayout),
};

structureddata := {
  [ffi] method [val] unmap(),
}
```
