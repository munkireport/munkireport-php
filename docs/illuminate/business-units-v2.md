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

## Planned Features ##

- A user can be part of multiple business units.
- A root-level business unit will be established to provide non-admin RBAC over all of the business units, like Global Viewers.
- Machine authentication (via Passphrase) will be disconnected from the machine group.
  - How are we gonna select a machine group given no information?
- A SAML attribute claim or OIDC token claim mapping can be provided to assign users to BU's at login time.
- The UI should provide an easy way to switch into a BU or machine group and back again to see dashboards that focus
  on only a portion of machines.
- The selected filter will persist through localStorage so that sign-in/out will preserve filter criteria.
- Machine groups may have external mapping criteria so that their membership can be managed eg. via Jamf or Intune

## Not Planned ##

- Machine group / BU in listings: not in this scope, in the enhanced query feature.

