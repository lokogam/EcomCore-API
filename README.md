# EcomCore-API

## Project Description

EcomCore-API is a simplified e-commerce platform demonstrating backend and frontend development skills using Laravel and Vue.js. This project, part of a technical test for a mid-level backend position, covers architectural design, cloud implementation, RESTful service development, and API documentation.

## Architecture

<p align="center">
<img src="Diagrama de Implementación de Arquitectura en AWS.png" width="600" alt="Architecture">
</p>

The project uses Laravel for the backend and Vue.js for the frontend, chosen for:
- Laravel's robust PHP framework with excellent API support
- Vue.js's reactive and user-friendly frontend framework

### Key Components:

- **Frontend (Vue.js)**: Interacts with the load balancer
- **Load Balancer (ELB)**: Distributes traffic among EC2 instances
- **Laravel API**: Manages requests, divided into Catalog and Order services
- **MySQL**: Database for products and orders
- **Redis**: Used for caching and queues
- **S3**: Stores static files in production
- **EC2**: Runs the Laravel application in the cloud
- **RDS**: Provides a managed MySQL database
- **ElastiCache**: Manages caching and queues
- **CloudFront**: CDN serving static files
- **IAM**: Manages permissions and access
- **CloudWatch**: Monitors and logs application activity

## Installation

### Prerequisites

- Docker
- Docker Compose

### Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/lokogam/EcomCore-API.git
   cd EcomCore-API
   ```

2. **Set up environment:**
   ```bash
   cp .env.example .env
   ```

   Add the following environment variables in the .env file:

 ```bash
    FRONTEND_URL=http://localhost:3000
    SANCTUM_STATEFUL_DOMAINS=localhost:3000
    SESSION_DOMAIN=localhost
    SQS_PREFIX=http://localstack:4566/000000000000
    SQS_QUEUE=your-queue-name
    AWS_ACCESS_KEY_ID=test
    AWS_SECRET_ACCESS_KEY=test
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=your-bucket-name
    AWS_ENDPOINT=http://localstack:4566
    AWS_USE_PATH_STYLE_ENDPOINT=true
    QUEUE_CONNECTION=sqs
   ```

3. **Install dependencies:**
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **Start services:**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generate application key:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Run migrations:**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

7. **Execute AWS setup:**
   ```bash
   ./vendor/bin/sail artisan aws:setup 
   ```

## API Documentation

Available at [http://localhost/api/documentation](http://localhost/api/documentation)

## Access

- Application: [http://localhost](http://localhost)
- Frontend repository: [VueShop-UI](https://github.com/lokogam/VueShop-UI.git)

---

Created by Duvan Gamboa  
Email: [duvangamboa8@gmail.com](mailto:duvangamboa8@gmail.com)  
LinkedIn: [Duvan Gamboa](https://www.linkedin.com/in/duvan-gamboa-5193951b2/)


