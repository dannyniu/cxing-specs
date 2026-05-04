<?= hc_H1(strtoupper(langname())." Sockets API") ?>

> This chapter forms an integral part of module "The Networking Module" -
> should "The Input/Output Module" be implemneted, this chapter along with any
> chapter constituting part of "The Input/Output Module" must be implemented
> in their entirity.

> This module depend on module "The Input/Output Module", should this module be 
> implemented, module "The Input/Output Module" must also be implemented.

<?= hc_H2("Additional Error Number Requirements") ?>

If the platform on which a <?= langname() ?> program runs uses separate namespace
for either the identifier or the value (or both) of error numbers for socket-specific
errors than the `errno` codes of other system-diagnosed errors, the implementation
shall map any socket-specific error codes to the namespace for `errno` codes.

**Note**: For example, on Windows, the Winsock 2 API have the `WSA` prefix to
error values such as `WSAEWOULDBLOCK`, `WSAEINTR`, etc. The implementation must
map those to `EWOULDBLOCK`, `EINTR`, etc. when they cast them into `null` for
failure returns.

<style>
  table.api-mapping {
    border-collapse:   separate;
    border-spacing:    3mm 1mm;
  }
</style>

<?= hc_H2("General") ?>

```
[Socket(GenericFile): subr socket(dom, typ, proto)] := {
  method send(str, flags),
  method recv(len, flags), // returns a string object.
  method sendto(str, flags, peer),
  method recvfrom(len, flags, peer), // returns a string object

  method shutdown(how),
  method bind(name),
  method connect(peer),
  method listen(backlog),
  method accept(), // returns a socket,

  method __get__(k),
  method __set__(k, v),
  method __copy__(),
  method __final__(),
}
```

The parameters `peer` and `name` for `sendto`, `recvfrom`, and `bind`, as well
as the return value from `accept` are 'socket address' objects described below.

The methods for a socket object return `null` that uncasts to `errno` codes
on failures. The correspondence between the socket methods and POSIX socket API
are as follow:

<table class="api-mapping">
  <thead>
    <tr>
    <th>Method Name
    <th>POSIX API
    <th>Return Value on Success
    <th>Special Return Values
  </thead>
  <tbody>
    <tr>
    <td>`send`
    <td>`send`
    <td>The number of bytes sent,
    <td>-
    <tr>
    <td>`recv`
    <td>`recv`
    <td>The received data as a string object,
    <td>0 on EOF.
    <tr>
    <td>`sendto`
    <td>`sendto`
    <td>The number of bytes sent,
    <td>-
    <tr>
    <td>`recvfrom`
    <td>`recvfrom`
    <td>The received data as a string object,
    <td>0 on EOF.
    <tr>
    <td>`shutdown`
    <td>`shutdown`
    <td>0
    <td>-
    <tr>
    <td>`bind`
    <td>`bind`
    <td>0
    <td>-
    <tr>
    <td>`connect`
    <td>`connect`
    <td>0
    <td>-
    <tr>
    <td>`listen`
    <td>`listen`
    <td>0
    <td>-
    <tr>
    <td>`listen`
    <td>`listen`
    <td>0
    <td>-
    <tr>
    <td>`accept`
    <td>`accept`
    <td>A socket for interacting with the accepted connection.
    <td>-
  </tbody>
</table>

The implementation shall define at least the following integer constants
corresponding respectively to those in the POSIX socket API:

- Address Families: `AF_INET`, `AF_INET6`, `AF_UNIX` (if platform supports), `AF_UNSPEC`,
- Socket types: `SOCK_STREAM`, `SOCK_DGRAM`,
- I/O flags: `MSG_NOSIGNAL`, `MSG_PEEK`, `MSG_WAITALL`,
- Others: `SHUT_RD`, `SHUT_RDWR`, `SHUT_WR`, `SOCK_CLOEXEC`.

The `__get__` and `__set__` methods of a socket object can be used to configure
and inspect socket options and properties.

<?= hc_H2("Socket Options and Properties") ?>

The `sockname` and `peername` properties are the socket addresses of the socket
object and its peer respectively, and are retrieved using the `__get__` method.

The options on the other hand consist of a 'level' and a 'name', separated by
a solidus `/`. Implementations shall support the following options, and may
support additional options, Boolean options are of type `long` and may assume
the value of either `true` or `false`.

<table class="api-mapping">
  <thead>
    <tr>
    <th>Option Name,
    <th>Configuration Value Type,
  </thead>
  <tbody>
    <tr>
    <td>`SOL_SOCKET/SO_ACCEPTCONN` (read-only)
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_BROADCAST`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_DEBUG`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_DOMAIN` (read-only)
    <td>Socket Domain Enumeration,
    <tr>
    <td>`SOL_SOCKET/SO_DONTROUTE`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_ERROR` (read-only, cleared on read)
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_KEEPALIVE`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_LINGER`
    <td>Socket Linger Object
    <tr>
    <td>`SOL_SOCKET/SO_OOBINLINE` (read-only)
    <td>Boolean (always `true`, see rationale below).
    <tr>
    <td>`SOL_SOCKET/SO_PROTOCOL`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_RCVBUF`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_RCVLOWAT`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_RCVTIMEO`
    <td>`double` interpreted in seconds.
    <tr>
    <td>`SOL_SOCKET/SO_REUSEADDR`
    <td>Boolean
    <tr>
    <td>`SOL_SOCKET/SO_SNDBUF`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_SNDLOWAT`
    <td>`long`
    <tr>
    <td>`SOL_SOCKET/SO_SNDTIMEO`
    <td>`double` interpreted in seconds.
    <tr>
    <td>`SOL_SOCKET/SO_TYPE` (read-only)
    <td>Socket Type Enumeration
    <tr>
    <td>`IPPROTO_IPV6/IPV6_JOIN_GROUP`
    <td>`ipv6_mreq`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_LEAVE_GROUP`
    <td>`ipv6_mreq`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_HOPS`
    <td>`long`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_IF`
    <td>`ulong`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_MULTICAST_LOOP`
    <td>Boolean
    <tr>
    <td>`IPPROTO_IPV6/IPV6_UNICAST_HOPS`
    <td>`long`
    <tr>
    <td>`IPPROTO_IPV6/IPV6_V6ONLY`
    <td>Boolean
    <tr>
    <td>`IPPROTO_TCP/TCP_NODELAY`
    <td>Boolean
  </tbody>
</table>

The newer RFC-9293 on the TCP protocol has advised against the use of
the 'urgent' flag in new applications. Although support for compatibility with
older applications that does use out-of-band from implementations are allowed,
<?= strtoupper(langname()) ?> is not one of those older language to begin with,
so we're omitting its support, and requiring that the `socket` subroutine to
set the `SO_OOBINLINE` option for all of its newly created sockets.

The `setsync` method of a socket (which is inherited from generic files)
shall be implemented using (i.e. on top of) the `TCP_NODELAY` option.

<?= hc_H2("Socket Address and Other Miscellaneous Types") ?>

Certain POSIX socket APIs expect pointers to structures that require language
bindings. Implementations may represent these structures as actual structures
in memory, and expose their members through the `__get__` and the `__set__`
methods, by reading from and writing to fields in the structures directly.
All subroutines that creates these so-called `DirectStructFieldAccess` types
shall initialize them to all-bit-zero before returning them.

```
[DirectStructFieldAccess] := {
  method __get__(k),
  method __set__(k, v),
  method __initset__(k, v),
  method __copy__(),
  method __final__(),
}

[sockaddr(DirectStructFieldAccess): subr sockaddr()]
```

- Mandatory Field(s):
  `sa_family`,
- Mandatory Fields for `AF_INET`:
  `sin_family`, `sin_port`, `sin_addr`
- Mandatory Fields for `AF_INET6`:
  `sin6_family`, `sin6_port`, `sin6_addr`.

The fields `*_family` and `*_port` shall be of integer type in appropriate range
in network byte order (e.g. 16-bit port number in host byte order converted to
big-endian represented in a `long`); `sin_addr` shall be of integer type in
unsigned 32-bit range in network byte order; `sin6_addr` shall be a 16-byte
string object.

```
[sock_linger(DirectStructFieldAccess): subr sock_linger()]
```

- Mandatory Fields:
  `l_onoff` (Boolean), `l_linger` (integer).

```
[ipv6_mreq(DirectStructFieldAccess): subr ipv6_mreq()]
```

- Mandatory Fields:
  `ipv6mr_multiaddr` (string object), `ipv6mr_interface` (integer).
