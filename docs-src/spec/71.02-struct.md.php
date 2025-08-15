<?= hc_H1("Library for the Describing Data Structure Layout") ?>

```
struct := {
  [ffi] method [val] __initset__(ref key, ref value),
};

union := {
  [ffi] method [val] __initset__(ref key, ref value),
};

packed := {
  [ffi] method [val] __initset__(ref key, ref value),
};

decl char, byte; // signed and unsigned 8-bit,
decl short, ushort; // signed and unsigned 16-bit,
decl int, uint; // signed and unsigned 32-bit,
decl long, ulong; // signed and unsigned 64-bit,
decl half, float, double; // binary16, binary32, binary64.
// decl _Decimal32, _Decimal64; // not supported yet.
// decl huge, uhuge, quad, _Decimal128; // too large.
```
