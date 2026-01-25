<?= hc_H1("Library for Floating Point Environment") ?>

Rounding Mode
----

**To Be Changed**: The exact form of the following functionality is being
redesigned, and will change over time.

```
subr fpmode(mode);
```

Returns the currently active rounding mode. If mode is one of the supported
mode, then set the current rounding mode to the specified mode. The value -1 is
guaranteed to not be any supported mode.

The following modes are supported:

- 0: round ties to even,
- 3: round towards positive,
- 5: round towards negative,
- 7: round towards zero.

The support for other modes are unspecified.

The encoding of modes are as follow:

- 0: nearest - rounding to nearest, and defer to next bits only on ties.
- 1: directed - always make decision based on next bits.

The next bits are as follow:

- 0<<1: even - the value with an even least significant digit is chosen,
- 1<<1: positive - the greater value is chosen.
- 2<<1: negative - the lesser value is chosen,
- 3<<1: zero - the value with lesser magnitude is chosen,
- 4<<1: away - the value with greater magnitude is chosen,
- 5<<1: odd - the value with an odd least significant digit is chosen.

Such encoding is chosen to cater to possible future extensions. Not all
possible rounding modes offer numerical analysis merit, as such some of the
combinations are not valid on some implementations.

Floating Point Exceptions
----

**To Be Changed**: The exact form of the following functionality is being
redesigned, and will change over time.

```
// Tests for exceptions
subr fptestinval(); // **invalid**
subr fptestpole(); // **division-by-zero**
subr fptestoverf(); // **overflow**
subr fptestunderf(); // **underflow**
subr fptestinexact(); // **inexact**

// Clears exceptions
subr fpclearinval(); // **invalid**
subr fpclearpole(); // **division-by-zero**
subr fpclearoverf(); // **overflow**
subr fpclearunderf(); // **underflow**
subr fpclearinexact(); // **inexact**

// Sets exceptions
subr fpsetinval(); // **invalid**
subr fpsetpole(); // **division-by-zero**
subr fpsetoverf(); // **overflow**
subr fpsetunderf(); // **underflow**
subr fpsetinexact(); // **inexact**

// Exceptions state.
subr fpexcepts(excepts);
```

The `fptest*`, `fpclear*`, and `fpset*` functions tests, clears, and sets the
corresponding floating point exceptions in the current thread.

The `fpexcepts` function returns the current exceptions flags. If `excepts` is
a valid flag, then the exceptions flag in the current thread will be set,
otherwise, it will not be set. The value 0 is guaranteed to be a valid flag
meaning all exceptions are clear; the value -1 is guaranteed to be an invalid
flag. The validity of other flag values are UNSPECIFIED. When the
implementation is being hosted by a C implementation, the encoding of `excepts`
is exactly that of `FE_*` macros, with the clear intention to minimize
unecessary duplicate enumerations as much as possible.
