#!/usr/bin/env python3

from diagrams import Cluster, Diagram
from diagrams.aws.compute import ECS, ECR
from diagrams.aws.management import Cloudwatch, ParameterStore
from diagrams.aws.network import VPC, InternetGateway, PublicSubnet
from diagrams.aws.security import IAMRole
from diagrams.aws.database import AuroraInstance

with Diagram("MunkiReport PHP on ECS Fargate Cluster wAurora Serverless"):
    with Cluster("VPC (BYO)"):
        with Cluster("ECS Cluster (BYO)"):
            with Cluster("munkireport-php service"):
                containers = [ECS("munkireport-php")]

        igw = InternetGateway("Internet Gateway (BYO)")

        containers >> igw

    ECR("Image Registry") << containers
    ParameterStore("SSM Parameter Store") << containers
    Cloudwatch("CloudWatch Logs") << containers
    AuroraInstance("MySQL Serverless") << containers
