<div class="pagebreak"></div>

<?= hc_H1("Expressions") ?>

-- TODO (2025-08-15): define all operations for all types. --

<?= hc_H2("Grouping, Postifix, and Unaries.") ?>

```
primary-expr % primary
: "(" expressions-list ")" % paren
| "[" expressions-list "]" % array
| identifier % ident
| constant % const
;
```

- `paren`: The value is that of the `expressions-list`.
- `array`: The value is an array consisting of
  elements from the `expressions-list`.
  -- TODO: define semantics for arrays. --
- `ident`: The value is whatever stored in the identifier.
- `const`: The value is that represented by the constant.

```
postfix-expr % postfix
: primary-expr % degenerate
| postfix-expr "->" primary-expr % nullcoalesce
| postfix-expr "[" expressions-list "]" % indirect
| postfix-expr "(" expressions-list ")" % funccall
| postfix-expr "." identifier % member
| postfix-expr "++" % inc
| postfix-expr "--" % dec
| object-notation % objdef
;
```

- `nullcoalesce`: If the value of `postfix-expr` isn't nullish, then the value
  is that of `postfix-expr`, otherwise that of `primary-expr`.
- `indirect`: Reads the key identified by `expressions-list` from the object
  identified by `postfix-expr`. The result is an lvalue.
- `funccall`: Calls `postfix-expr` as a function, given `expressions-list`
  as parameters. If `postfix-expr` is a `member`, then its `postfix-expr`
  is provided as the `this` parameter to a potential method call. The result
  is the return value of the function. See
  <?= hcNamedSection("Subroutines and Methods") ?> for further discussion.
- `member`: Reads the key identified by the spelling of `identifier` from
  the object identified by `postfix-expr`. The result is an lvalue.
- `inc`: Increment `postfix-expr` by 1. The result is the pre-increment value
  of `postfix-expr`. `postfix-expr` MUST be an lvalue.
- `dec`: Decrement `postfix-expr` by 1. The result is the pre-decrement value
  of `postfix-expr`. `postfix-expr` MUST be an lvalue.
- `objdef`: See <?= hcNamedSection("Type Definition and Object Initialization Syntax") ?>

```
unary-expr % unary
: postfix-expr % degenerate
| "++" unary-expr % inc
| "--" unary-expr % dec
| "+" unary-expr % positive
| "-" unary-expr % negative
| "~" unary-expr % bitcompl
| "!" unary-expr % logicnot
;
```

- `inc`: Increment `unary-expr` by 1. The result is the post-increment value
  of `unary-expr`. `unary-expr` MUST be an lvalue.
- `dec`: Decrement `unary-expr` by 1. The result is the post-decrement value
  of `unary-expr`. `unary-expr` MUST be an lvalue.
- `positive`: The result is that of `unary-expr` implicitly converted to a
  number if necessary.
- `negative`: The result is the negative of `unary-expr`, which is implicitly
  converted to a number if necessary.
- `bitcompl`: The result is the bitwise complement of `unary-expr` under
  integer context.
- `logicnot`: The result is 0 if `unary-expr` is non-zero, and 1 if
  `unary-expr` compares equal to 0 (both +0 and -0).

For `inc` and `dec` in `unary` and `postfix`, and `positive` and `negative`,
operation occur under arithmetic context. For `bitcompl` and `logicnot`, the
operation occur under integer context.

<?= hc_H2("Arithmetic Binary Operations") ?>

```
mul-expr % mulexpr
: unary-expr % degenerate
| mul-expr "*" unary-expr % multiply
| mul-expr "/" unary-expr % divide
| mul-expr "%" unary-expr % remainder
;
```

- `multiply`: The value is the product of `mul-expr` and `unary-expr`.
- `divide`: The value is the quotient of `mul-expr` divided by `unary-expr`.
- `remainder`: The value is the remainder of `mul-expr` modulo `unary-expr`.

Division on integers SHALL round towards 0. The remainder computed SHALL be
such that `(a/b)*b + a%b == a` is true. If the divisor is 0, then the
quotient of division becomes positive/negative infinity of type `double` if
the sign of both operands are same/different, while the remainder
becomes `NaN`, with the "**invalid**" floating point exception signalled.

All of `mulexpr` occur under arithmetic context, with the exception
of `remainder`, which occur under integer context.

```
add-expr % addexpr
: mul-expr % degenerate
| add-expr "+" mul-expr % add
| add-expr "-" mul-expr % subtract
;
```

- `add`: The value is the additive sum of `add-expr` and `mul-expr`.
- `subtract`: The value is the difference of subtracting
  `mul-expr` from `add-expr`.

All of `addexpr` occur under arithmetic context.

<?= hc_H2("Bit Shifting Operations") ?>

```
bit-shift-expr % shiftexpr
: add-expr % degenerate
| bit-shift-expr << add-expr % lshift
| bit-shift-expr >> add-expr % arshift
| bit-shift-expr >>> add-expr % rshift
;
```

- `lshift`: The value is the left-shift `bit-shift-expr` by `add-expr` bits.
- `arshift`: The value is the arithmetic-right-shift `bit-shift-expr` by
  `add-expr` bits. This is done without regard to the actual signedness
  of the type of `bit-shift-expr` operand.
- `rshift`: The value is the logic-right-shift `bit-shift-expr` by
  `add-expr` bits. This is done without regard to the actual signedness
  of the type of `bit-shift-expr` operand.

All of `shiftexpr` occur under integer context.

**Side Note**: There was left and right rotate operators. Since there's
only a single 64-bit width in native integer types, bit rotation become
meaningless. Therefore those functionalities are offered in the standard
library method functions `u{64,32,16,8}{l,r}rot(amount)`.

<?= hc_H2("Arithmetic Relations") ?>

```
rel-expr % relops
: bit-shift-expr % degenerate
| rel-expr "<" bit-shift-expr % lt
| rel-expr ">" bit-shift-expr % gt
| rel-expr "<=" bit-shift-expr % le
| rel-expr ">=" bit-shift-expr % ge
;
```

- `lt`: True if and only if `rel-expr` is
  less than `bit-shift-expr`.
- `gt`: True if and only if `rel-expr` is
  greater than `bit-shift-expr`.
- `le`: True if and only if `rel-expr` is
  less than or equal to `bit-shift-expr`.
- `ge`: True if and only if `rel-expr` is
  greater than or equal to `bit-shift-expr`.

All of `relops` occur under arithmetic context. If either operand is NaN,
then the value of the expression is false.

```
eq-expr % eqops
: rel-expr % degenerate
| eq-expr "==" rel-expr % eq
| eq-expr "!=" rel-expr % ne
| eq-expr "===" rel-expr % ideq
| eq-expr "!==" rel-expr % idne
;
```

- `eq`: True if left operand equals the right under arithmetic context;
  or if one is `null`, the other is of the integer value 0.
  False otherwise.
- `ne`: True if left operand does not equal the right operand.
  This includes the case where one operand is of integer values
  other than 0 and the other is `null`. False otherwise.
- `ideq`: True if left operand equals the right under arithmetic context;
  or if both are `null`. False otherwise.
- `idne`: True if left operand does not equal the right operand.
  This includes the case where one operand is of the integer value 0
  and the other is `null`. False otherwise.

<?= hc_H2("Bitwise Operations") ?>

```
bit-and % bitand
: eq-expr % degenerate
| bit-and "&" eq-expr % bitand
;

bit-xor % bitxor
: bit-and % degenerate
| bit-xor "^" bit-and % bitxor
;

bit-or % bitxor
: bit-xor % degenerate
| bit-or "|" bit-xor % bitor
;
```

- `bitand`: The value is the bitwise and of 2 operands.
- `bitxor`: The value is the bitwise exclusive-or of 2 operands.
- `bitor`: The value is the bitwise inclusive-or of 2 operands.

All of the bitwise operations occur under integer context.

<?= hc_H2("Boolean Logics") ?>

```
logic-and % logicand
: bit-or % degenerate
| logic-and "&&" bit-or % logicand
;

logic-or % logicand
: logic-and % degenerate
| logic-or "||" logic-and % logicor
| logic-or "??" logic-and % nullcoalesce
;
```

- `logicand`: if the first operand is zero or `null`, then
  this is the result and the second operand is not evaluated,
  otherwise, it's the value of the second operand.
- `logicor`: if the first operand is non-zero and non-`null`, then
  this is the result and the second operand is not evaluated,
  otherwise, it's the value of the second operand.
- `nullcoalesce`: Refer to `postfix-expr`.

<?= hc_H2("Compounds") ?>

```
cond-expr % tenary
: logic-or % degenerate
| logic-or "?" expressions-list ":" cond-expr % tenary
;
```

- `tenary`: The `logic-or` is first evaluated.
  If it's non-zero and non-`null`, then `expressions-list` is evaluated;
  otherwise, `cond-expr` is evaluated;
  The result is whichever `expressions-list` or `cond-expr` evaluated.

```
assign-expr % assignment
: cond-expr % degenerate
| unary-expr "=" assign-expr % directassign
| unary-expr "*=" assign-expr % mulassign
| unary-expr "/=" assign-expr % divassign
| unary-expr "%=" assign-expr % remassign
| unary-expr "+=" assign-expr % addassign
| unary-expr "-=" assign-expr % subassign
| unary-expr "<<=" assign-expr % lshiftassign
| unary-expr ">>=" assign-expr % rshiftassign
| unary-expr "&=" assign-expr % andassign
| unary-expr "^=" assign-expr % xorassign
| unary-expr "|=" assign-expr % orassign
| unary-expr "&&=" assign-expr % conjassign
| unary-expr "||=" assign-expr % disjassign
;
```

- `directassign`: writes the value of `assign-expr` to `unary-expr`.
- *compound assignments*: writes the computed value to `unary-expr`.

See <?= hcNamedSection("Object/Value Key Access") ?> for further discussion.

```
expressions-list % exprlist
: assign-expr % degenerate
| expressions-list "," assign-expr % exprlist
;
```

- `exprlist`: A list of expressions.
  - In the context of function calls and arrays, all entities constitutes
    the list, and elements are evaluated in arbitrary order.
  - In the context of an expression phrase, `expressions-list` is
    first evaluated, then `assign-expr` is evaluated next, and
    the value of the expression is that of `assign-expr`.
