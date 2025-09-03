<?= hc_H1("Functions") ?>

```
function-declaration % funcdecl
: "subr" identifier arguments-list statement % subr
| "method" identifier arguments-list statement % method

| "ffi" "subr" type-keyword identifier arguments-list ";" % ffisubr
| "ffi" "method" type-keyword identifier arguments-list ";" % ffimethod
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
| "ref" % ref
| "long" % long
| "ulong" % ulong
| "double" % double
;
```

For `subr` and `method`, the function so defined or declared is a non-FFI
function. The type of its parameters must be `val` or `ref`. Its return type
is implicitly `val` and is not spelled out.

For `ffisubr` and `ffimethod`, the function so defined or declared is an FFI
function. The type of its parameters can be `val`, `ref`, `long`, `ulong`,
`double`, and they're passed to the function as described in
<?= hcNamedSection("Calling Conventions and Foreign Function Interface") ?>.
The return type MUST NOT be as prohibited in
<?= hcNamedSection("Subroutines and Methods") ?>.

For `subr` and `method`, function body MUST be either `emptystmt`, in which
case the `function-declaration` declares a function, or `brace`, in which case
it defines a function. FFI functions (`ffisubr` and `ffimethod`) can be
declared, but cannot be defined in <?= langname() ?>.

The type and order of parameters between all declarations and the definition of
the function MUST be consistent, furthermore, whether a function is a method or
a subroutine, is or is not an FFI function MUST be consistent. The name of the
parameters may be changed in the source code of a program. Depending on the
context, this may provide the benefit of both explanative argument naming in
declaration, and avoidance identifier collision in function definition when
the argument is appropriately renamed.
