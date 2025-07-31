<div class="pagebreak"></div>

Language Semantics
====

<a id="objs-vals">Objects and Values</a>
----

An *object* may have properties, properties may also be called members.

**Note**: The word "property" emphasizes the semantic value of the said
component, while the word "member" emphasizes its identification. Both words
may be used interchangeably consistent with the intended point of perspective.

A *full object* is a type in the language. The term "object" when used in such
way may also refer to an value of the object type, which is a "full object".

An *opaque object* on the other hand has no langauge-accessible properties,
the outside world can only have interaction with it through functions.
Opaque objects have interoperable data structure across different compiled
implementations on a particular platform.

**Note**: Functions in compiled implementations follow platform ABI's calling
convention. Because certain opaque objects may need to be used in functions
compiled on different implementations, the consistency of its structure layout
on a particular platform is essential.

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

Other native objects (may) exist in the language.

**Note** The data structure for the value native objects are further defined
to enable the interoperability of certain language features. Values are such
described to enable discussion of "lvalue"s, alternative implementations
may use other conceptual models for lvalues should they see fit.

<a id="obj-val-key-access">Object/Value Key Access</a>
----

As described in [objects and values](#objs-vals) objects have properties.

<a id="obj-key-read">To read a key from an object</a>:
- <div>if the key is not defined on the object, a native object results
  representing an assignable destination and a null value - it consist of</div>
  <code>(value_proper=null, type=null,
        scope=&lt;the object&gt;, key=&lt;the key&gt;)</code>
- <div>if the key is defined on the object, a native object results
  representing an assignable destination and its *current* value,
  consisting of</div>
  <code>(value_proper, type: &lt;the stored value and type&gt;,
         scope, key: &lt;see above&gt;)</code>

<a id="obj-key-write">To write a key onto an object</a>:
- the new value is assigned to the identified key on the object,
  with the following exceptions:
- if the write is a compound assignment (i.e. any assignment of form other
  than `directassign`), then the key is read from the object, the computation
  part of the compound assignment is performed, and the result is stored
  written to they key on the object.

**Note** Compound assignment is different from loading the values from both
sides of the assignment operator, perform the computation, then storing the
result into the key, as the latter performs the read on the lvalue twice.

When a key is being deleted from an object:
- the value associated with the key on the object is finalized,
  when the finalization is complete, the key is considered not defined
  on the object from this point onwards until it's being written to again.

-- TODO: define "finalize". --

<a id="subr-methods">Subroutines and Methods</a>
----

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

**Note** In compiled implementations, functions follow the platform ABI's
calling convnetion. The distinction between subroutines and methods means that
the first parameter of a method always receive the `this` handle, which is
usually a pointer, while the parameters in a subroutine have no such
restriction. To facilitate the correct passing of parameters, this
necessitates the distinction of methods and subroutines as distinct types.

Some methods are applicable to wide range of certain types of values that may
not allow member property assignment. In such case, a special type of method
known as *trait calls* are used. Trait calls receive as its `this` argument,
an opaque handle to the value native object, whereas member methods receive the
parent object as their `this` argument. Trait and member methods calls are
distinguished through their calling syntax. Because trait and member
method calls expect handles to different types of objects, the behavior
is UNSPECIFIED if one is called in the context of another.

For each of subroutine, methods, and trait calls, they have FFI and non-FFI
variants. FFI stands for foreign function interface. In non-FFI variants their
arguments are dynamically typed, and can receive arguments can be passed either
by value or by reference. For FFI variants, the type of their arguments have to
be declared explicitly.

(Non-FFI) subroutines, methods, trait calls, and FFI subroutines, FFI methods,
and FFI trait calls are 6 distinct types.

The `val` and `ref` Function Operand Interfaces
----

For non-FFI functions, when a parameter is declared with `val`, then
the corresponding argument is passed by value; when declared with `ref`, then
passed by reference.

No type of function may return `ref` for the simple reason that certain value
that may potentially be returned are of "temporary" storage duration - they
exist only on the stack frame of called function, and are destroyed when they
go out of scope. Adding compile-time check to verify that such variables are
not returned as reference are more complex to implement than simply just
outlawing them outright.

Types and Special Values
====

The `long` and `ulong` types
----

The `long` type is a signed 64-bit integer type with negative values having
two's complement representation. The `ulong` type is an unsigned 64-bit
integer type. Both types have sizes and alignments of 8 bytes.

**Note** 32-bit and narrower integer types don't exist natively, primarily
because of the year 2038 problem. However, their respective type objects,
as well as that for `float`/`binary32` floating point type are defined in the
standard library to interpret data structures stored in data buffer objects.

-- TODO: `bool` as alias for `long`, add rationale. --

The `double` type
----

The `double` type is the floating point number type. It should correspond to
the IEEE-754 (a.k.a. ISO/IEC-60559) `binary64` type - that is, it should have
1 sign bit, 11 exponent bits, and 52 mantissa bits. The type have sizes and
alignment of 8 bytes.

The `str` type
----

The string type `str` is not a built-in type, instead, it's an object type
defined in the standard library. The string type has significance in the
`indirect` member access operator in a `postfix-expr` postfix expression.

The `true` and `false` special values
----

The special value `true` is equal to 1 in type `long`.
The special value `false` is equal to 0 likewisely.

The `null` and `NaN` special values
----

The `null` special value results in certain error conditions. Accessing
any properties (unless otherwise stated) results in `null`; calling `null`
as if it's a function results in `null`. `null` compares equal to itself.

The `NaN` special value represents exceptional condition in mathematical
computation. `NaN` does not compare equal to any number, or to itself.

Both `null` and `NaN` are considered nullish in coalescing operations.

See [Numerics and Maths](#num-maths) for furher discussion.

Implicit Type and Value Conversion
----

For many arithmetic operations, types are converted implicitly.
If and when it applies, the following rule determines the resulting type:
- operations involving only `long`s results in `long` operands;
- operations involving `ulong` but not `double` results in `ulong` operands;
- operations involving `double` results in `double`

Some special values are converted used under certain contexts:

- in type `long` and `ulong`, `null` is converted to 0 (a.k.a. `false`).
- in type `double`, `null` is converted to `NaN`.

The special value `NaN` always have type `double`.
