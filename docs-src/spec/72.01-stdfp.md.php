<?= hc_H1("Library for Floating Point Environment") ?>

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
