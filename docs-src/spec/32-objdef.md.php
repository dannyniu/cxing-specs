<?= hc_H1("Type Definition and Object Initialization Syntax") ?>

There's a simple syntax in <?= langname() ?> for creating
compound objects and types:

```
decl Complex := namedtuple() { 're': double, 'im': double };
decl I := Complex() { 're': 0, 'im': 1 };
decl sockaddr := dict() { 'host': "example.net", 'port': 443 };
```

In the above scenario,

- `namedtuple()` *factory function* creates such object that is
  a *type object* that creates another type object with 2 members
  named "re" and "im", this type is assigned to `Complex`,
  which is then used to create a "complex number"
  with the value of the imaginary unit;
- `dict()` factory function creates a type object that creates a
  dictionary, initializing `sockaddr` with 2 members - "host" with
  the value of "example.net" and "port" with 443.

`namedtuple`, `Complex`, and `dict` are "type objects", of which,
with `namedtuple` being sort of a meta.

A type object contains an method property named `__initset__` declared as follow:

```
[ffi] method [val] __initset__(ref key, ref value);
```

The `__initset__` function may be defined in <?= langname() ?> or in a foreign
language - if the latter, then calling conventions for foreign function
interface must be followed per
<?= hcNamedSection("Calling Conventions and Foreign Function Interface") ?>.

```
objdef-start % objdefstart
: objdef-start-comma % comma
| objdef-start-nocomma % nocomma
;

objdef-start-comma % objdefstartcomma
: objdef-start-nocomma "," % genrule
;

objdef-start-nocomma % objdefstartnocomma
: postfix-expr "{" postfix-expr ":" assign-expr % base
| objdef-start-nocomma "," postfix-expr ":" assign-expr % genrule
;

object-notation % objdef
: postfix-expr "{" "}" % empty
| objdef-start "}" % some
;
```

The `postfix-expr` MUST NOT be `inc` or `dec`. Furthermore, if `postfix-expr`
is `degenerate`, then the primary expression MUST NOT be `array` or `const`.

On encountering a `postfix-expr` that is a type object, the key-value pairs
enclosed in the braces delimited by commas are taken and the `__initset__`
method is called on them in turn. The key is the value of the postfix
expression on the left side of the colon, while the value is that of the
assignment expression on the right side of the colon. After this completes,
the newly created object will receive a property named `__proto__`,
which will be assigned the value of `postfix-expr`.

The `array` production of primary expressions is a syntax sugar that invokes
`__initset__` with elements in the `expressions-list` as value and successive
integer indicies as key, starting with 0.
