<div class="pagebreak"></div>

<?= hc_H1("Numerics and Maths") ?>

**Note** Much of this section is motivated by a desire to have a self-contained
description of numerics in commodity computer systems, as well as an/a
interpretation / explanation / rationale of the standard text that's at least
more useful in terms of practical usage than the standard text itself.

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
  - the input is outside the domain of the operation, e.g. sqrt(-1).

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


<?= hc_H2("Reproducibility and Robustness") ?>

Floating points have a fixed significand width as well as limited range(s) of
exponents, as such, they're very similar to *scientific notations*, further
as such, they suffer from the same **inaccuracy** problems as any notation that
truncates a large fraction of value digits. However, this do yield a favorable
trade-off in terms of implementation (and to some extent, usage) **efficiency**.

IEEE-754 recommends that language standard provide a mean to derive a sequence
(graph actually, if taken dependencies into account) of computation in a way
that is deterministic. Many C compilers provide options that make maths work
faster using arithmetic associativity, commutativity, distributivity and other
laws (e.g. *fast-math* options), <?= langname() ?> make no provision that
prevents this - people favoring efficiency and people favoring accuracy should
both be audience of this language.

The root cause of calculation errors stem from the fact that the significand of
floating point datum are limited. This error is amplified in calculations. A
way to quantify this error is using the "unit(s) in the last place" - ULP.
There are various definitions of ULP. Vendors of mathematical libraries may at
their discretion document the error amplification behavior of their library
routines for users to consult; framework and library standards may at their
discretion specify requirements in terms error amplification limits. Developers
are reminded again to recognize, and evaluate at their discretion, the
trade-off between accuracy and efficiency.

Because of the existence of calculation errors, floating point datum are
recommended as instrument of data exchange. In fact, earlier versions of the
IEEE-754 standard distinguished between interchange formats and arithmetic
formats. Because arithmetics and the format where it's carried out are
essentially black-box implementation details, the significance of arithmetic
formats is no longer emphasized in IEEE-754.

The recommended methodology of arithmetic, is to first derive procedure of
calculation that is a simplified version of the full algorithm, eliminating
as much amplification of error as possible, then feed the input datum elements
into the algorithm to obtain the output data. The procedure so derived should
take into account of any exceptions that might occur.

For example, `(a+b)(c+d) = ac+ad + bc+bd` have
2 additions and 1 multiplication on the left-hand side and
3 additions and 4 multiplications on the right-hand side.

a program may first attempt to calculate the left hand side, because it has
less chance of error amplification. However, if the addition of `c` and `d`
overflows but they're individually small enough such that their multiplication
with either `a` and `b` won't overflow, yet the sum of `a` and `b` underflows
in a certain way that's catastrophic, the the whole expression may become `NaN`.

In this case, a fallback expression may then compute the right-hand side of the
expression, possibly yielding a finite result, or at least one that
arithmetically make sense (i.e. infinity).

The result of computation carried out using such "derived" procedure will
certainly deviate from the result from of a "complete" algorithm. Developers
should recognize that robustness may be more important in some applications
than they may expect. In the limited circumstances where an application in
reality is less important, or in fact be prototyping, developer may at their
careful discretion, excercise less engineering effort when coding a numerical
program.

Finally, it is recognized that large existing body of sophisticated numerical
programs are written using 3rd-party libraries, and/or using techniques that're
under active research and not specified and beyond the scope of many standards.
Developers requiring high numerial sophistication and robustness are encouraged
to consult these research, and evaluate (again) the accuracy and efficiency
requirements at their careful discretion.
