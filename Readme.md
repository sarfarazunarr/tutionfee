# Apni Tution Payment Portal

## Overview

Apni Tution Payment Portal is a web application designed for students to manage their tuition payments efficiently. This platform integrates Stripe for secure payment processing and offers various features to enhance the user experience.

## Features

1. **Stripe Payment Integration**
   - Students can pay their tuition fees securely using Stripe.
   - Supports various payment methods offered by Stripe.

2. **User Account Management**
   - Students can create and manage their accounts.
   - Options to update personal information.
   - Account deletion functionality available.

3. **Payment Status Tracking**
   - Students become "active" upon successful payment.
   - Automatic status updates based on payment history.

4. **Email Notifications**
   - Automated email alerts when a student becomes inactive.
   - Reminders for upcoming payments or overdue fees.

## How It Works

1. **Registration**: Students sign up and create an account.
2. **Payment**: Use Stripe to pay tuition fees.
3. **Status Update**: Account status changes to "active" after successful payment.
4. **Monitoring**: System tracks payment due dates and sends notifications.
5. **History**: Students can view their complete transaction history.

## Getting Started

To set up and run the project locally, follow these steps:

1. **Database Setup**:
   - Create a new MySQL database named `tutionfee`.

2. **Table Creation**:
   - Run the `setup.php` file to create the necessary tables in the `tutionfee` database.

3. **Email Configuration**:
   - Open the `sendEmail.php` file.
   - Add your email address and password in the appropriate fields.

4. **Automated Checking**:
   - For Linux/Unix servers: Add `script.bat` to your server's cron job.
   - For Windows servers: Add `script.bat` to the Task Scheduler.

5. **Stripe API Configuration**:
   - Open `pay-fee.php` and `process-payment.php` files.
   - Add your Stripe API key in the designated fields.

6. **Run the Application**:
   - Start your local server and navigate to the project directory.
   - Access the application through your web browser.

Note: Ensure that you have the necessary permissions and server requirements met before running the application.
## Technologies Used

- Backend: (Specify your backend technology)
- Frontend: (Specify your frontend framework/library)
- Database: (Specify your database system)
- Payment Processing: Stripe API
- Email Service: (Specify your email service provider)


## Support

For any queries or support, please contact [sarfarazunarr@gmail.com](mailto:sarfarazunarr@example.com).

