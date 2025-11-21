<div class="pagebreak"></div>

<?= hc_H1("Language Semantics") ?>

<?= hc_H2("Objects and Values") ?>

An *object* may have properties, properties may also be called members.

**Note**: The word "property" emphasizes the semantic value of the said
component, while the word "member" emphasizes its identification. Both words
may be used interchangeably consistent with the intended point of perspective.

The internals of an object is largely opaque to the language.
The primary interface to objects are functions that operates on them.

**Note**: Functions in compiled implementations follow platform ABI's calling
convention. Because certain opaque object types (such as the string type)
in the runtime may need to be used in functions compiled on different
implementations, the consistency of their structure layout is essential.

A *native object* is a construct for describing the language. It has a
fixed set of properties, and are copied by value; mutating a native
object does not affect other copies of the object.

An *value* is a native object with the following properties:
1. the value *proper*,
2. a *type*,
3. for an *lvalue* - which can be the left operand of respective assignment
   expression, there's the following additional properties:
   1. a *scope object* - this can be a block, an object;
      for `sharable` types, this can also be the "global" scope,
   2. a *key* - this identifies/is the name of the lvalue under the scope.

Other native objects may be introduced in the future.

All values have a (possibly empty) set of type-associated properties that're
immutable. These type-associated properties take priority over other
properties. The behavior is UNSPECIFIED when these properties are written to.

**Note**: The data structure for the value native objects are further defined
to enable the interoperability of certain language features. Values are such
described to enable discussion of "lvalue"s, alternative implementations
may use other conceptual models for lvalues should they see fit.

<?= hc_H2("Object/Value Key Access") ?>

As described in <?= hcNamedSection("Objects and Values") ?> objects have
properties. The key used to access a value on an object is typically a string
or an integer.

When the key used to access a property is an integer, there may be a mapping
from the integer to a string defined by the implementation of the runtime.
Portable applications SHOULD NOT create objects with mixed string and integer
keys. All implementations of the runtime SHALL guarantee there's no collision
between any key that is the valid spelling of an identifier and any integer
between 0 and 10<sup>10</sup> inclusive.

**Note**: The limit was chosen for efficiency reasons. While implementing a
number to string conersion would immediately solve the issue of collision
between numerical and identifier keys, it's slightly inefficient. A second
option would be to pad the integer word with bytes that can never be valid in
identifiers, this would be the best of both worlds. Yet considering most
applications won't be needing such big array, and those that do would probably
go for the string type in the standard library, a limit is set so that
plausible real-world applications and implementations can enjoy the efficiency
enabled by such latitude.

<a id="obj-key-read">To read a key from an object</a>:
1. if the key refers to one of the type-associated properties:
   1. a native object results consisting of:
      - value-proper: the value of this property,
      - type: the type of this property.
2. if the key is not one of the type-associated properties:
   1. if the key `__get__` is one of the type-associated properties, then
      this method is used to retrieve the actual property:
      1. this method is called with the object as its `this` parameter,
      2. this method is called with the key as a `val`,
      3. its return value is augmented with the 'scope' and 'key' being the
         object and the key used to access this property, to yield an lvalue.
   2. if the key `__get__` is not defined as one of the type-associated
      properties, then an lvalue being `null` augmented with 'scope' and 'key'
      being the object and the key used to access this property is returned.

**Note**: The return value from 2.1.3. may be `null`. The `null` resulting from
step 2.2. shall be a Morgoth, because there exists no diagnostic information.

<a id="obj-key-write">To write a key onto an object</a>:
* For the purpose of this section, it is assumed that the storing of the value
  onto the object is done using the `__set__` type-assocaited method property.
  The object is passed as the `this` parameter, the key as the first parameter
  as a `val`, and the value as the value as the the second parameter as
  a value native object. See
  <?= hcNamedSection("Calling Conventions and Foreign Function Interface") ?>
- the new value is assigned to the identified key on the object,
  with the following exceptions:
- if the write is a compound assignment (i.e. any assignment of form other
  than `directassign`), then the key is read from the object, the computation
  part of the compound assignment is performed, and the result is stored
  written to they key on the object.

**Note**: Compound assignment is different from loading the values from both
sides of the assignment operator, perform the computation, then storing the
result into the key, as the latter performs the read on the lvalue twice.

When a key is being deleted from an object:
* For the purpose of this section, it is assumed that the deletion of the value
  from the object is done using the `__unset__` type-associated method
  property. The object is passed as the `this` parameter, the key as the first
  parameter as a `val`.
- any resources used by the value associated with the key on the object is
  finalized, if the `__final__` method property exists on the object, then it's
  called, the key is then removed from the object, after which the member
  identified by the key is considered not defined on the object from this point
  onwards (until it's being written to again).

**Note**: Destruction of values and finalization of resources are further
discussed in <?= hcNamedSection("Finalization and Garbage Collection") ?>.

<?= hc_H2("Subroutines and Methods") ?>

Both *subroutines* and *methods* are codes that can be executed in the
language, the distinction is that methods have an implicit `this` parameter
while subroutines don't - for compiled implementations, this is significant,
as it causes difference in parameter passing under a given calling convention.

Subroutines and methods are distinct types, as such there's no restriction that
subroutines have to be called directly through identifiers or that methods
have to be identified through a member access.

- When accessed from the key of an object:
  - a method carries an implicit `this` parameter,
  - a subroutine does not carry the implicit `this`.
- When invoked by name:
  - the implicit `this` in a method is `null`.
  - a subroutine is invoked as is.

Previously (before 2025-11-03), there had been FFI (foreign function interface)
subroutines and functions. Because it's impossible to determine the prototype
of the functions called from properties of objects, it is therefore unsafe to
call FFI functions. On the same safety note, calling convention of (non-FFI)
subroutine and methods are changed to take into account for potentially missing
parameters.

**Note**: In a previous revision, there was a note claimed that `this` being a
pointer handle. The idea back then was that when <?= langname() ?> runtime is
implemented with SafeTypes2, certain APIs of the library can be used without
modification. However, better runtime implementation stratagy was discovered
which resulted in the introduction of type-associated properties.
And so `this` parameter is received as a `val` in all (currently one) type(s)
of methods. Still, to facilitate the correct passing of parameters, it
necessitates the distinction between methods and subroutines.
As of 2025-10-27, the `ref` argument type is removed entirely.

<?= hc_H1("Types and Special Values") ?>

The `long` and `ulong` types
----

The `long` type is a signed 64-bit integer type with negative values having
two's complement representation. The `ulong` type is an unsigned 64-bit
integer type. Both types have sizes and alignments of 8 bytes.

**Note**: 32-bit and narrower integer types don't exist natively, primarily
because of the year 2038 problem and issue with big files. However, respective
type objects for smaller integers, as well as those for `float`/`binary32` and
other floating point types are defined in the standard library to interpret
data structures in byte strings.

The keyword `bool` is used exclusively as an alias for the type `long`, there
is no restriction that a `bool` can store only 0 or 1, it exist primarily for
programmers to clarify their intentions.

The `double` type
----

The `double` type is the floating point number type. It should correspond to
the IEEE-754 (a.k.a. ISO/IEC-60559) `binary64` type - that is, it should have
1 sign bit, 11 exponent bits, and 52 mantissa bits. The type have sizes and
alignment of 8 bytes.

The `str` type
----

The string type `str` is not a built-in type, instead, it's an opaque object
type defined in the standard library. The string type has significance in the
`indirect` member access operator in a `postfix-expr` postfix expression.

The `true` and `false` special values
----

The special value `true` is equal to 1 in type `long`.
The special value `false` is equal to 0 likewisely.

The `null` and `NaN` special values
----

The `null` special value results in certain error conditions. Accessing
any properties (unless otherwise stated) results in `null`; calling `null`
as if it's a function results in `null`.

There are 2 kinds of `null`s
- The 'blessed' `null` contains diagnostic information in the form of a signed
  integer (i.e. `long`), that may be obtained by *uncasting*.
- "Morgoth" - which uncasts to another "Morgoth" `null`. This kind of `null` is
  to be used when no diagnosis is needed.

All `null`s compares equal to each other barring uncasting.

The `NaN` special value represents exceptional condition in mathematical
computation. `NaN` does not compare equal to any number, or to itself.
Uncasting an `NaN` results in its bit pattern being re-interpreted as a `long`.

Both `null` and `NaN` are considered nullish in coalescing operations.

See <?= hcNamedSection("Numerics and Maths") ?> for furher discussion.

<?= hc_H2("Implicit Type and Value Conversion") ?>

Values and/or their types may be converted used under certain contexts:
- The types `long` and `ulong` are collectively "integer context";
- the type `double` is the "floating point context";
- the types `long`, `ulong`, and `double` are collectively "arithmetic context".

The "implicit type and value conversion" apply to multiple operands in such way
that there's one common type (or special value) that is the same regardless of
the order of the operands. This conversion is defined in terms of a binary
operation that is associative and commutative, so that any binary expression
operator that is associative and commutative preserve this property
regardless of the types of the operands.

Under a integer context:
- all opaque objects have a single value of 1,
- floating point real numbers are converted by discarding fractional part,
- the conversion of infinities and NaN are UNSPECIFIED.

Under the floating point context:
- integers are converted preserving value to the extent allowed by precision.
- all opaque objects are converted to `+1.0`.

Under arithmetic context:
- before the following occur, opaque objects are converted to 1 in `long`.
- operations involving only `long`s results in `long` operands;
- operations involving `ulong` but not `double` results in `ulong` operands;
- operations involving `double` results in `double`;

The special value `null` is treated specially:
- for operators that evaluates the order between operands, operands are
  converted to `null`, which is neither less nor greater than any integer or
  floating point number - this is known as the *order evaluation conversion*.
- for operators that computes a value from operands, the `null` is converted
  to the integer `0`, or if there're `double`, to `+0.0` -
  this is known as the *value computation conversion*

Operators shall document whether they evaluate the order of, or compute a
value from operands. In general, operators that returns true/false predicate
from arithmetic operands evaluates the order, while ones that computes a value
would evaluate to arithmetic types.

**Note**: The special value `NaN` always have type `double`.

**Note**: It was considered to have certain operations in integer context that
involved floating points to have NaNs, but this was dropped for 2 simple
reasons: 1st, the current *conversion* rule is much simpler written, and 2nd,
there exist prior art with JavaScript.
