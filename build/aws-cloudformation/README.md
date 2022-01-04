# MunkiReport PHP on AWS Elastic Container Service #

This folder contains example CloudFormation stacks for deploying MunkiReport-PHP on
AWS ECS.

## Requirements ##

**BYO ECS Cluster!**
The stack does not deploy an ECS cluster for a few reasons:

- The ECS cluster resource takes a long time to bring up and tear down, so it isn't
  easy to iterate on MunkiReport like this.
- You might already have a VPC configured that you want to use. Ditto for security
  groups, NAT gateways etc.
- If you already had an ECS cluster running in EC2 mode, this CloudFormation template
  would make no sense if it created another one. You'd be charged for capacity you
  already had.

### Manually deploy an ECS Cluster ###

(TODO Clicky instructions)

## Templates ##

### MunkiReport on ECS using SQLite (Public IP) ###

Template: [munkireport-ecs-sqlite.yaml](munkireport-ecs-sqlite.yaml) 

- Good for cheap demo or testing.
- Absolutely useless if you want to keep any data.
- Even more useless if you need performance of any kind.
- Not even secure, make sure you only allow your private IP to access the application.
  - FWIW, if you don't have a requirement for roaming clients just lock it down in any case.

### MunkiReport on ECS using Aurora Serverless MySQL (Public IP) ###

- Like SQLite except you get to keep your data across restarts.

### MunkiReport on ECS with Load Balancer/NAT using Aurora Serverless MySQL ###

- Like the above, except you have options to prevent this service being an easy target for attacks.
- You can make use of Certificate Manager to provide SSL/TLS termination which is a far easier solution.




