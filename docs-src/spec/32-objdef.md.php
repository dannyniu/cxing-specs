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
method __initset__(key, value);
```

**Note** The parameters of the `__initset__` method property were changed from
`ref` to `val`. For one, most usages would have keys and values as literals,
so it doesn't make sense to have references to them. Other issue is that, there
haven't been a way to signify the end of list. This is now changed to use the
setting of the existing `__proto__` property to the type object for signifying
the end-of-list. As of 2025-10-27, the `ref` argument type is removed completely,
further as of 2025-12-26, operand types' annotations are eliminated altogether.

```grammar
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
| auto-index % array
;
```

The `postfix-expr` MUST NOT be `inc` or `dec`. Furthermore, if `postfix-expr`
is `degenerate`, then the primary expression MUST NOT be `const`.

On encountering a `postfix-expr` that is a type object, the key-value pairs
enclosed in the braces delimited by commas are taken and the `__initset__`
method is called on them in turn. The key is the value of the postfix
expression on the left side of the colon, while the value is that of the
assignment expression on the right side of the colon. After this completes,
the `__initset__` method is invoked with `__proto__` as key and
the value of `postfix-expr` to signify the end, and then, the now value
of `postfix-expr` becomes the value of the `object-notation` expression.

**Note**: As such, the property names `__initset__` and `__proto__` are
*RESERVED* for the "Type Definition and Object Initialization Syntax".

```grammar
auto-index-start-comma % array_piece
: postfix-expr "[" assign-expr "," % base
| auto-index-start-comma assign-expr "," % genrule
;

auto-index % array
: auto-index-start-comma "]" % complete
| auto-index-start-comma assign-expr "]" % streamline
;
```

The `array` rule is a syntax sugar that invokes `__initset__` with elements
in the `expressions-list` as value and successive integer indicies as key,
starting with 0.
