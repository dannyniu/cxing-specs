<?= hc_H1("Functions") ?>

```
function-declaration % funcdecl
: "subr" type-keyword identifier arguments-list statement % subr
| "method" type-keyword identifier arguments-list statement % method
;

arguments-list % arglist
: "(" ")" % empty
| arguments-begin ")" % some
;

arguments-begin % args
: "(" type-keyword identifier % base
| arguments-begin "," type-keyword identifier % genrule
;

type-keyword % typekw
: "val" % val
| "obj" % obj
| "in" % in
| "out" % out
| "void" % void
| "long" % long
| "ulong" % ulong
| "double" % double
;
```

There is no semantic differenciation among type keywords except `void` - all of
the other keywords are represented as a value native object.

The type keyword of the parameters MUST NOT be `void`. The type keyword of the
return value (i.e. the 2nd term in the `subr` and `method` production) can be
any of the allowed type keywords.

Because <?= langname() ?> is a dynamically typed language, the type of the
arguments and return values are not enforced at all, the presence of the keywords
however is a syntactic requirement to disambiguate argument list from
parenthesised expressions list, and to provide annotation to the semantic of
parameters.

**Note**: Before 2025-12-16, the type keyword `void` was `null` - this caused
interpretation conflict between the type keyword and the special value, and is
therefore changed.

When the function body is `emptystmt`, the `function-declaration` declares a
function; when it's `brace`, it defines a function. The `this` keyword MUST NOT
appear in the function body of a subroutine.

When the end of the function body is reached without an explicit `return` phrase,
a Morgoth `null` is implicitly returned.

The type keyword and order of parameters and the type keyword of the return
value between all declarations and the definition of the function MUST be
consistent, furthermore, whether a function is a method or a subroutine. The
name of the parameters may be changed in the source code of a program.
Depending on the context, this may provide the benefit of both explanative
argument naming in declaration, and avoidance identifier collision in function
definition when the argument is appropriately renamed.

**Note**: Before 2025-10-27, there were FFI methods. This had been removed,
because methods are attached to properties of objects, and their prototypes
cannot be reliably determined unless all parameters are of uniform type,
only then, could the *number* of arguments be determined. As of 2025-11-03,
all FFI are removed - this is because impossibility with determining the
prototype of the said FFI functions when they're called from object properties.
