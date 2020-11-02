# Business Units v2 #

## Slack Feedback ##

- Moving groups by changing enrol/pairing key works well for some, and not well for others.
- A user cannot be part of multiple business units currently, this is a huge downside.
- Kevin wanted the passphrase and the group membership split.

## GitHub Feedback ##

- 458: Need to sort Business Units (if you have more than a whole page of them)
- 466: Export all machines under a specific BU
- 583: Config overrides per BU
- 1155: Machine group column in all lists
- 1039: Cant be admin of two BU's
- 1049: Audit log
- 1287: Add BU that can see but not modify all others.

## Scope of Redesign ##

- A user must be able to be part of multiple Business Units in different Capacities.
- A global viewer role must be established, perhaps a *ROOT* BU where permissions govern every sub-BU could be an okay
  model.
- Consider splitting key from group.
- Consider ADLDAP or SAML claim -> BU membership automatically.

