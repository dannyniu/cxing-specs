<?= hc_H1("Library for the Describing Data Structure Layout") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

```
decl char, byte; // signed and unsigned 8-bit,
decl short, ushort; // signed and unsigned 16-bit,
decl int, uint; // signed and unsigned 32-bit,
decl long, ulong; // signed and unsigned 64-bit,
decl half, float, double; // binary16, binary32, binary64.
// decl _Decimal32, _Decimal64; // not supported yet.
// decl huge, uhuge, quad, _Decimal128; // too large.

[subr struct()] := {
  method __initset__(key, value),
};

[subr packed()] := {
  method __initset__(key, value),
};

[subr union()] := {
  method __initset__(key, value),
};
```

The representations for `char`, `byte`, `short`, `ushort`, `int`, `uint`,
`long`, `ulong`, `half`, `float`, and `double` are explained in the comments
following their description; their alignments are the same as their size.
These are known as primitive types.

All of these type objects have a method member called `from`, which performs
_explicit type and value conversion_ - unlike implicit type and value conversion,
the resulting type are determined by the type object. The method takes
one argument and converts it to a value representable in the destination type:
- For integer types, the result is the `long` language type for signed types,
  and `ulong` for unsigned types.
- For floating point types, the result is `double`.

A `struct_inst` object represents an instance of structure that is suitabl for
use in a call to the `map()` method of the `str` type, representing a structure
with members laid out sequentially and suitably align. A `packed_inst` is
similar, but with no alignment - all members are packed back-to-back.
A `union_inst` creates a structure layout object with all members having the
same start address at byte 0 and alignment of the strictestly-align member.

Each object of type `struct_inst`, `packed_inst`, and `union_inst` are
type objects. They're initialized with members using the syntax as described in
<?= hcNamedSection("Type Definition and Object Initialization Syntax") ?>; and
are created using the `struct()`, `packed()`, and `union()` factory functions
respectively.

Primitive types and structure layout object may be array-accessed to create
array types of respective types.

For example:

```
decl AesBlock = union() { 'b': byte[16], 'w': uint[4] };
decl Aes128Key = AesBlock[11];
```

The variable `AesBlock` holds a structure layout object of 128 bits,
and `Aes128Key` holds the 11 round keys for an AES-128 cipher.
