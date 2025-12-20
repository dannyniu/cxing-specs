<?= hc_H1("Features") ?>

To best reflect the intent of the design, the specification shall be
programmer-oriented. The purpose of features will be explained, with examples
provided on how they're to be used. The syntax and semantic definitions follow.

Memory and Thread Safety
----

The language does not expose pointers - to data or to function - only opaque
object handles. It uses reference counting with garbage collection to ensure
memory safety. It has separate type domain for `sharable` types catered to
multi-threaded access, and `exclusive` types for efficient access within a
single thread; only `sharable` types can be declared globally.

Null safety.
----

It's typical to desire *some* result come out of a failing program, it is
even more desirable that the failure of a single component doesn't deny
the service of users, it's very desirable that error recovery can be
easy to program, and it's undesirable that errors cannot be detected.

In <?= langname() ?>, errors occur in the forms of nullish values. For the
special value `null`, accessing any member of it yields `null`, and calling
a `null` as a function returns `null`. Nullish values can be substituted with
other alternative values that programs recover from errors.

```
// We do not know the schema of this object, but we know it can be
// one of the two alternatives. Here the "??" punctuation is the
// nullish coalescing operator:
timescale = mp4box.movie.timescale ??
            mp4box.fragments[0].timescale ??
            mp4file.timescale;
```

Nullish NaNs
----

A bit of background first.

The IEEE-754 standard for floating point arithmetic specifies handling of
exceptional conditions for computations. These conditions can be handled
in the default way (default exception handling) or in some alternative
ways (alternative exception handling).

The 1985 edition of the standard described exceptions and their default
handling in section 7, and handling using traps in section 8. These were
revised as "exceptions *and* default exception handling" in section 7
as well as "altenate exception handling *attributes*" in section 8 in the
2008 edition of the standard - these "attributes" are associated with "blocks"
which (as most would expect) are group(s) of statements. Alternate exception
handling are used in many advanced numerical programs to improve robustness.

As a prescriptive standard, it was intended to have language standards to
describe constructs for handling floating point errors in a generic way that
abstracts away the underlying detail of system and hardware implementations.
In doing so, the standard itself becomes non-generic, and described features
specific to some languages that were not present in others.

The <?= langname() ?> language employs null coalescing operators as
general-purpose error-handling syntax, and make it cover NaNs by making them
nullish. As an unsolicited half-improvement, I (@dannyniu) propose
the following alternative description for "alternate exception handling":

> Language ought to specify ways for program to transfer the control of
> execution, or to evaluate certain expressions when a subset (some or all)
> of exceptions occur.

As an example, the continued fraction function in code example A-16 from
"Numerical Computing Guide" of Sun ONE Studio 8
(<?= hcURL("https://www5.in.tum.de/~huckle/numericalcomputationguide.pdf
") ?>, accessed 2025-08-15)
can be written
in <?= langname() ?> as:

```
subr void continued_fraction(val N, val a, val b, val x, obj p)
{
    decl f, f1, d, d1, pd1, q;
    decl j;

    f1 = 0.0;
    f = a[N];
    for(j=N-1; j>=0; j--)
    {
        d = x + f;
        d1 = 1.0 + f;
        q = b[j] / d;
        f1 = (-d1 / d) * q _Fallback f1 = b[j] * pd1 / b[j+1];
        pd1 = d1;
        f = a[j] + q;
    }
    p.f = f;
    p.f1 = f1;
}
```

Reproducibility issues treated in the standard are further discussed in
<?= hcNamedSection("Reproducibility and Robustness") ?>
