<div class="pagebreak"></div>

<?= hc_H1("Library for Floating Point Environment") ?>

> This chapter forms an integral part of the language and
> its implementation is mandatory.

Rounding Mode
----

A rounding mode consist of a rounding direction and a rounding strategy.
A valid rounding mode is always a positive integer.
The numerical value for a rounding mode in <?= langname() ?> is defined as follow:

- A rounding direction is one of the following:
  - `0\A`: even - the value with an even least significant digit is chosen,
  - `0\P`: positive - the greater value is chosen.
  - `0\N`: negative - the lesser value is chosen,
  - `0\Z`: zero - the value with lesser magnitude is chosen,
  - `0\I`: away - the value with greater magnitude is chosen,
  - `0\O`: odd - the value with an odd least significant digit is chosen.

- A rounding strategy is one of the following:
  - `0\A`: nearest - rounding to nearest, and defer to rounding direction only on ties.
  - `0\D`: directed - always make decision based on rounding direction.

In the encoding for a rounding mode, the rounding direction occupies the
bottom 6 bits, while the rounding strategy occupies the next higher 6 bits.

**Note**: `0\A` is zero in radix-64 literals, and it is desired that the default
mode is round-to-nearest ties-to-even and that it has a numerical value of 0.
The values for other rounding direction are chosen as follow: P - positive,
N - negative, Z - zero, I - infinities, O - odd.

```
subr feRndMode();
subr feRndMode_manual(v);
[subr feRndMode_auto(v)] := {
  method __copy__(),
  method __final__(),
}
```

The subroutine `feRndMode` gets the current rounding mode, encoded as
described above.

The subroutine `feRndMode_manual` sets the rounding mode dynamically. If the
argument `v` is a valid rounding mode and can be accepted by the implementation,
then the current rounding mode is set to that encoded by `v`. On success, `v`
is returned, otherwise `null` is returned.

The subroutine `feRndMode_auto` creates and returns a 'smart rounding mode' object.
The mode is set according the value encoded by `v` in the same way
as `feRndMode_manual`. During creation, the object remembers the currently set
rounding mode, which is restored when the object is destroyed.

Mandatory Operations Not Expressed as Operators
----

```
// arithmetic:
subr sqrt(x); // square root of x
subr fma(x, y, z); // `x*y+z` rounded once
subr fabs(x); // absolute value,
subr copysign(x, y); // returns the number x except with the sign bit of y.

// relations:
subr fmax(a,b); // the greater of a and b, or the other if one is NaN.
subr fmin(a,b); // the lesser of a and b, or the other if one is NaN.
subr fpmax(a,b); // returns what `fmin` would not return, propagates NaN.
subr fpmin(a,b); // returns what `fmax` would not return, propagates NaN.

// conversion:
subr dtoa(x); // converts double to decimal string
subr atod(s); // converts decimal string to double

// classification:
subr fpclassify(x);
subr isfinite(x);
subr isinf(x);
subr isnan(x);
subr isnormal(x);
subr signbit(x);

// exponent manipulation:
subr ilogb(x);
subr scalbn(x,n);

// rounding to integers:
subr ceil(x);
subr floor(x);
subr trunc(x); // discard fractions.
subr round(x); // ties to away.
subr roundeven(x); // ties to even.
subr rint(x); // based on current mode.
```

Recommended Operations Provided to Applications on Best-Effort Basis
----

The following operations are required to be accessible by applications. Their
computation may be inaccurate up to one part in a hundredth. Implementations
are required to provide them, so that the numerical quality of applications
that uses them can improve over time as the implementation improves.

**Note**: The above (im-)precision 'requirement' should _NEVER_ be interpreted
as allowance for inferior implementations, rather, the requirement of providing
them is assurance for applications to never need to "reinvent the wheel"
Quoting from the 3rd edition of "*Modern C*" by Jens Gustedt:

> Nowadays, implementations of numerical functions should be high quality,
> be efficient, and have well-controlled numerical precision. Although any of
> these functions could be implemented by a programmer with sufficient
> numerical knowledge, you should not try to replace or circumvent them. Many
> of them are not just implemented as C functions but also can use
> processor-specific instructions.

```
subr sin(x);
subr cos(x);
subr tan(x);
subr sinpi(x);
subr cospi(x);
subr tanpi(x);

subr asin(x);
subr acos(x);
subr atan(x);
subr atan2(y, x);
subr asinpi(x);
subr acospi(x);
subr atanpi(x);
subr atan2pi(y, x);

subr sinh(x);
subr cosh(x);
subr tanh(x);
subr asinh(x);
subr acosh(x);
subr atanh(x);

subr exp(x);
subr exp2(x);
subr exp10(x);
subr expm1(x);
subr exp2m1(x);
subr exp10m1(x);

subr log(x);
subr log2(x);
subr log10(x);
subr logp1(x);
subr log1p(x); // for the nostalgic.
subr log2p1(x);
subr log10p1(x);

subr cbrt(x);
subr compoundn(x, n);
subr hypot(x, y);
subr pow(x, y); // the slow careful one,
subr pown(x, n);
subr powr(x, y); // the careless fast one,
subr rootn(x, n);
subr rsqrt(x);
```

For `compoundn`, `pown`, application shall ensure the 2nd argument `n` is of
integer type to attain better accuracy.

For `rootn`, application shall ensure the 2nd argument `n` is of integer type
to attain better accuracy. Further, application shall ensure that if `x` is
negatively signed, then application shall ensure `n` is odd to ensure correctness
Otherwise, implementation may resort to computing `pow(x, 1.0/n)`.

**TODO 2026-04-19**: other operations
