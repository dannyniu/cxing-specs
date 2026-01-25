<?= hc_H1("Library for Process Management") ?>

> This chapter forms an integral part of "The Process Management Module" -
> should "The Process Management Module" be implemneted, this chapter along
> with any chapter constituting part of "The Process Management Module" must be
> implemented in their entirity.

> This module depend on "The Input/Output Module", should this module be
> implemented, "The Input/Output Module" must also be implemented.

```
[subr CmdInterp()] := {
  method Argv(v),
  method Envp(v),
  method ObtainPipeForStdin(),
  method ObtainPipeForStdout(),
  method ObtainPipeForStderr(),
  method SetSourceForStdin(fp),
  method SetDestForStdout(fp),
  method SetDestForStderr(fp),
  method SetCwd(path),
  [method Exec()] := {
    method __get__(k),
    method Wait(),
    method Terminate(),
    method Kill(),
    method Stop(),
    method Continue(),
  },
};
```

The `CmdInterp` function creates a _preparation context_ used for executing a program.

The `Argv` method passes the argument `v` as an integer-keyed object consisting
of a set of strings as the "argument vector" (i.e. the `argv` parameter to the
C `main` function) to the context.

The `Envp` method passes the argument `v` as a string-keyed object consisting
of a set of strings as the "environment variables" (i.e. available through
the `getenv` function in C) to the context.

The `ObtainPipeFor*` functions create pipes and attach appropriate reading
or writing end to the standard input/output/error of the child process, and
closing unused end in respective the process.

The `SetSourceForStdin` method sets the file handle `fp` as the reading source
for standard input of the new process. The `SetDestFor*` methods set `fp`
as the writing destination for standard output and standard error respectively.

The `SetCwd` method sets the initial value for the current working directory
for the new process.

On success, the functions `Argv`, `Envp`, `ObtainPipesFor*` 
and `Set{Source,Dest}For*` functions returns the preparation context, allowing
successive operations to be chained. On error, a blessed `null` that uncasts
to an implementation-defined status code is returned.

The `Exec` method executes and returns a _process handle_, or a blessed `null`
that uncasts to an implementation-defined status code is returned.

The `__get__` method of the process handle is used to retrieve a few
non-type-associated properties:
- `infile`: The writing end of the pipe for standard input -
  `null` if `ObtainPipeForStdin` wasn't called when creating the process.
- `outfile`: The reading end of the pipe for standard output - 
  `null` if `ObtainPipeForStdout` wasn't called when creating the process.
- `errfile`: The reading end of the pipe for standard error -
  `null` if `ObtainPipeForStderr` wasn't called when creating the process.
  
The `Wait` method blocks the calling thread until the process referred to
by the process handle terminates, and returns its exit status.

The `Terminate` method terminates the process referred to by the process handle
The `Kill` method serves a similar function, but do it more forcibly, without
giving a chance for the process to do any cleanup.

The `Stop` method and `Continue` method stops (i.e. pauses) and continues the
execution of the process refered to by the process handle.
