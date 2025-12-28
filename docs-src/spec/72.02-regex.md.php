<?= hc_H1("Regex") ?>

> This chapter forms an integral part of "The Regex Module" - should this
> module be implemneted, this chapter along with any chapter constituting
> part of "The Regex Module" must be implemented in their entirity.

```
[bre_comp(regex, cflags) | ere_comp(regex, cflags)] := {
  // An opaque object representing a compiled regular expression.
  method split(subject);
  method match(subject);
  method capture(subject);
  method replace(subject, replacement, limit);
};
```

The `bre_comp()` and `ere_comp()` functions compiles a regular expression based
on the "Basic Regular Expression" and "Extended Regular Expression" syntax
specified by POSIX. All regex features up to POSIX-2017 are mandatory.

The `cflags` are expressed as radix-64 digits, whose correspondence with
POSIX compile flag constants are as follow:
- `0\i`: `REG_ICASE` - regex is executed without regard to case.
- `0\n`: `REG_NEWLINE` - the lines (delimited by the LINE-FEED character)
  in the subject string is considered individually.

The `split()` method splits the subject string into a 0-base-indexed array
of strings. The `match()` method determines whether the subject string can
be matched by the regular expression.

The `capture()` method matches the subject string, putting matched
subexpressions in array (starting from the index 1), the (entire) matched
portion of the subject string in the 0th element of the said array,
then return the array.

The `replace()` method replaces `limit` number of occurences of the substring
matching the regex, with `replacement`. Each occurences of `$<n>` where `<n>`
is a single decimal digit is replaced with the _n_-th subexpression in the
regex. If `<n>` is 0, then it's replaced with the whole matched portion of
the subject string. If `limit` is `-1`, then all occurences shall be replaced.
