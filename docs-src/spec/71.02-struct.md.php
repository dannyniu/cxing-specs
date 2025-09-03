<?= hc_H1("Library for the Describing Data Structure Layout") ?>

```
decl char, byte; // signed and unsigned 8-bit,
decl short, ushort; // signed and unsigned 16-bit,
decl int, uint; // signed and unsigned 32-bit,
decl long, ulong; // signed and unsigned 64-bit,
decl half, float, double; // binary16, binary32, binary64.
// decl _Decimal32, _Decimal64; // not supported yet.
// decl huge, uhuge, quad, _Decimal128; // too large.

struct := {
  [ffi] method [val] __initset__(ref key, ref value),
};

union := {
  [ffi] method [val] __initset__(ref key, ref value),
};

packed := {
  [ffi] method [val] __initset__(ref key, ref value),
};
```

The representations for `char`, `byte`, `short`, `ushort`, `int`, `uint`,
`long`, `ulong`, `half`, `float`, and `double` are explained in the comments
following their description; their alignments are the same as their size.
These are known as primitive types.

The `struct` built-in object creates a structure layout object suitable for use
in a call to the `map()` method of the string type, representing a structure
with members laid out sequentially and suitably align. `packed` is similar, but
with no alignment - all members are packed back-to-back. The `union` built-in
object creates a structure layout object with all members having the same start
address - i.e. position 0.

Primitive types and structure layout object may be array-accessed to create
array types of respective types.

For example:

```
decl AesBlock = union { 'b': byte[16], 'w': uint[4] };
decl Aes128Key = AesBlock[11];
```

The variable `AesBlock` holds a structure layout object of 128 bits,
and `Aes128Key` holds the 11 round keys for an AES-128 cipher.
