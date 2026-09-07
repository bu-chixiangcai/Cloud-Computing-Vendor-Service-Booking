output "alb_dns_name" {
  description = "Public URL to reach the app through (http://<this>)."
  value       = module.alb.alb_dns_name
}

output "rds_endpoint" {
  value     = module.rds.db_endpoint
  sensitive = true
}

output "s3_bucket_name" {
  value = module.s3.bucket_id
}

output "secret_arn" {
  value = module.secrets.secret_arn
}

output "asg_name" {
  value = module.asg.asg_name
}

output "alerts_topic_arn" {
  description = "SNS topic for CloudWatch alerts. Subscribe an email endpoint in the AWS Console."
  value       = aws_sns_topic.alerts.arn
}
