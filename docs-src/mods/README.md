MODs - Minutes of Discussions/Debates
====

The term MOD stand for both minute of a discussion/debate and moderation, and
as it implies, it serves as record for reaching concensus when there are issues
of any kind surrounding the language.

To reiterate, the language is meant to be stable, rarely changed, and ideally
develop once and fixed forever. Recognizing the spirit of free software that
people to enjoy the freedom of modifying their copies of program and sharing
them with peers, a channel must be open for feedback and anticipate the eventual
adoption of new ideas.

The timescale of such change are on magnitude of years to a decade. Adoption of
new features should follow the below hard criteria:
1. The Feature has high demand,
2. The Feature admit non-controvercial implementation and doesn't break
   the language, or the reference and third-party implementations.
   In other word, its implementation must be mature and well-understood.
3. The Feature must not find itself alien in the language.

Format
----

As a minute, there must be relevant discussion, while debates must be held
formally. The format of debates may be defined in a future time.

An MOD starts with the problem statement - this should include problems
encountered in actual programming using the language, an open-ended but
clear-goal question, core conflict of particular opinions, and/or a
request for comment on something specific.

The MOD then document the summary of arguments of participants, their proposal
if any, along with relevant rationales that they're encouraged to provide.

The MOD might be provided back to participants for review, correction, and
further discussion. The most important part is that participants can read
in full concentration, to better understand how the arguments of their own
and others stack up, and provide opportunity to articulate better.

The MOD may be revised and extended following discussions, and the feedback
loop continues, towards the goal of reaching concensus - _EVEN IF_ the concensus
is there cannot be a concensus.

Practice
----

The primary arena for discussion is Email and the Issues feature provided by
code repository hosting services. In most cases, general discussions won't lead
to MODs. MODs only results when:
1. there's a clear-goal discussion which has the potential of getting distracted,
2. the discussion is of value to people beyond original participants,
3. the participants believe a full record of discussion is useful eventually,
4. the participants requests a MOD.

Before a MOD is adopted and recorded into the repository, the participants should
draft one on their own, and circulate among all participants. The MOD should be
available as plaintext downloadable attachments sent over email, and should be
Markdown-decorated to aid readability.

After a MOD is determined to be acceptable for adoption, the participant(s) may
open a pull request, placing the MOD in the same directory as this readme file,
naming it as `{YYYY-MM-DD}.TBD-{Topic}`, where `{YYYY-MM-DD}` is the date when
the original discussion formed, and `{Topic}` is a summary of topic of the
discussion, preferrably including the goal or an abbreviated problem statement.

The adopted MODs have TBD replaced with monotonically increasing lower-case
Roman numeral specific to each date - that is, for each date prefix, there's
a separate listing of numeric literals, one for each adopted MOD.

MODs are only records of discussion, any participants may write, circulate, and
publish MODs. ___MODs are in no way commitments to language features___.
