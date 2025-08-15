<div class="pagebreak"></div>

<?= hc_H1("Numerics and Maths") ?>

<?= hc_H2("Rounding") ?>

IEEE-754 specifies the following rounding modes:

- **roundTiesToEven**: This is MANDATORY and SHALL be the default within
  a thread when the thread starts. The floating point value closest to the
  infinitely precise result is returned. If there are two such values,
  the one with an even digit value at the position corresponding to the
  least significant of the least significant digits of the two values
  will be returned.

- **roundTowardPositive**: The least representable floating point value
  no less than the infinitely precise result is returned.

- **roundTowardNegative**: The greatest representable floating point value
  no greater than the infinitely precise result is returned.

- **roundTowardZero**: The representable floating point value with
  greatest magnitude no greater than that of the infinitely precise result
  is returned.

The standard library provides facility for setting and querying
the rounding mode in the current thread. The presence of other
rounding modes (e.g. **roundTiesToAway**, **roundToOdd**, etc.)
are implementation-defined.

<?= hc_H2("Exceptional Conditions") ?>

Infinity and NaNs are not numbers. It is the interpretation of @dannyniu that
they exist in numerical computation strictly to serve as error recovery and
reporting mechanism.

IEEE-754 specifies the following 5 exceptions:

- **invalid**: known as "invalid operation" in standard's term. This is when:
  - operations involving signalling NaNs,
  - "cancellation of infinities" in additive, multiplicative, or some other
    domains. Examples include subtracting infinity from infinity, multiplying
    0 with infinity, or dividing 0 with 0 or infinity with infinity.
  - the input is outside the domain of the operation.

- **pole**: known as "division by zero" in standard's term. A pole results
  when operation by an operand results in an infinite limit. Particular cases
  of this include 1/0, tan(90&deg;), log(0), etc.

- **overflow**: this is when and only when the result exceeds the magnitude
  of the largest representable finite number of the floating point data type
  after rounding. The data type is `double` a.k.a. `binary64` in our language.

- **underflow**: this is when a tiny non-zero result having an absolute value
  below <var>b<sup>emin</sup></var>, where <var>b</var> is the radix of the
  floating point data type - 2 in our case , and <var>emin</var> is, in
  our case -1022.

  <small>**Note**: <var>emin</var> can be derived as:
  <var>2 - 2<sup>ebits-1</sup></var>, where <var>ebits</var> is the number of
  bits in the exponents, which is 11 in our case.</sup></small>

- **inexact**: this is when the result after rounding differs from what would
  be the actual result if it were calculated to unbounded precision and range.

The standard library provides facility for querying, clearing,
and raising exceptions. Alternate exception handling attributes
are implemented in the language as error-handling flow-control
constructs, such as null-coalescing expression and phrases operators,
as well as execution control functions.


<?= hc_H2("Reproducibility") ?>

-- TODO: 2025-07-31 --
