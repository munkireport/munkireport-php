#!/usr/bin/env python3

from diagrams import Cluster, Diagram
from diagrams.aws.compute import ECS, ECR
from diagrams.aws.management import Cloudwatch, ParameterStore
from diagrams.aws.network import VPC, InternetGateway, PublicSubnet, ALB, NATGateway, PrivateSubnet, Endpoint
from diagrams.aws.security import IAMRole
from diagrams.aws.database import AuroraInstance

with Diagram("MunkiReport PHP on ECS Fargate Cluster wAurora Serverless ALB"):
    with Cluster("VPC (BYO)"):
        with Cluster("Private Subnet"):
            # subnet = PrivateSubnet("Private")
            with Cluster("munkireport-php service"):
                containers = [ECS("munkireport-php")]

            AuroraInstance("MySQL Serverless") << Endpoint("Interface Endpoint") << containers


        with Cluster("Public Subnet"):
            nat = NATGateway("NAT Gateway")
            containers >> nat >> InternetGateway("Internet Gateway (BYO)")
            containers << ALB("Application Load Balancer")




    with Cluster("AWS Public Zone"):
        nat >> ParameterStore("SSM Parameter Store")
        nat >> Cloudwatch("CloudWatch Logs")
        nat >> ECR("Image Registry")
