#!/usr/bin/env python3

from diagrams import Cluster, Diagram
from diagrams.aws.compute import ECS, ECR
from diagrams.aws.management import Cloudwatch, ParameterStore
from diagrams.aws.network import VPC, InternetGateway, PublicSubnet
from diagrams.aws.security import IAMRole

with Diagram("MunkiReport PHP on ECS Fargate Cluster"):
    with Cluster("BYO VPC"):
        with Cluster("munkireport-php service"):
            containers = [ECS("munkireport-php")]

        igw = InternetGateway("BYO Internet Gateway")

        containers >> igw

    ECR("ecr munkireport-php:latest") << containers
    ParameterStore("ssm parameters /munkireport/*") << containers
