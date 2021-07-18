# MunkiReport PHP on AWS Elastic Container Service #

This folder contains example CloudFormation stacks for deploying MunkiReport-PHP on
AWS ECS.

## Requirements ##

The stack does not deploy an ECS cluster for a few reasons:

- The ECS cluster resource takes a long time to bring up and tear down, so it isn't
  easy to iterate on MunkiReport like this.
- You might already have a VPC configured that you want to use.

### Manually deploy an ECS Cluster with FARGATE_SPOT provider ###

(TODO)

## Templates ##


