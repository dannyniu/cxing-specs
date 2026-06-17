<?= hc_H1("Error Code Namespace") ?>

On a given platform, there may be more than one namespace for error codes.
The `errno` namespace being part of the standard C library, it is recommended
that they be identity mapped to uncasted `null` values. 

The base requirement for all error codes (including the `errno` set and all 
other sets), is that their identifier have mutually distinct values, barring 
exemption from referenced specifications (e.g. `EAGAIN` and `EWOULDBLOCK`). 
This means that 2 identifiers from 2 different sets must not have the same value.

The next sets of error codes concerns sockets and network name resolution.
The the `WSAE*` codes and the `EAI_*` codes are relevant here because of their
inclusion in the <?= langname() ?> standard library. The `WSAE*` socket interface
error codes had been required to be mapped their `errno` counter parts; 
the `EAI_*` codes are required to have distinct values from all other sets,
including the `errno` set. For `EAI_SYSTEM`, the implementation should substitute
the value of `errno` of relevant system call when casting nulls. Relevant
quotes from the POSIX standard:

> - `EAI_SYSTEM`:
>   - A system error occurred. The error code can be found in `errno`.

On Windows, there's a set of codes from the `GetLastError` system call. 
Library vendors may have their own sets of error codes, which also must not
collide with any other error codes from other sets.

Assuming all error codes occpy no more than 32 bits, the high 32 bits of an
uncasted `null` is used for namespace separation as follow:
1. The library choose for itself a name, possibly suffixed with a major version,
2. the name is hashed with CRC-32 as specified in ISO/IEC 8802-3:1996,
   (The description for the `cksum` utility in the POSIX standard contains a
   C language implementation of this algorithm) with the most significant bit
   cleared, and the next bit set.
3. The checksum is then left-shifted by 32 bits, and added with any error codes
   defined in the library's namespace, and exported as identifier into <?= langname() ?>.
