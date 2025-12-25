<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class MainFeatureFaqSeeder extends Seeder
{
    public function run()
    {
        // List of all 22 barangay IDs
        $barangayIds = range(1, 22);

        // All FAQ entries
        $faqs = [
            [
                'question' => 'Why do I need to accept the Data Privacy Policy?',
                'answer' => 'You must accept the policy to ensure that you understand how your personal information will be used and protected within the app. You cannot register without accepting it.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How do I fill out the registration form and what are the requirements?',
                'answer' => 'Complete the multi-section registration form: Personal Information (full name, sex, birthdate, civil status), Contact & Location Information (email, phone number, zone, barangay, voter status), and Security & Proof of Residency. Submit the form and wait for the Barangay Secretary’s approval.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'What can I see on the Home/Dashboard screen?',
                'answer' => 'The Home screen shows a feed of community updates and announcements, including barangay events posted by the ABC President and Barangay Officials.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How can I request barangay documents?',
                'answer' => 'Go to the Barangay Services tab, tap “Request” on the document you need, select the purpose, pay the required fee, and submit your request.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'What is the Concern feature and how does it work?',
                'answer' => 'The Concern feature allows residents to report issues within their community. Users can select the type of concern, provide a detailed description, specify the location, upload a supporting photo, and submit the report. This ensures the municipality receives immediate, geo-tagged feedback.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How do I submit a concern?',
                'answer' => 'Go to the Concern tab, select the type of concern, provide a detailed description and location, upload a supporting photo, and tap “Submit”.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How do I track the status of my transactions?',
                'answer' => 'In the Transaction screen, view the progress tracker: Pending → Approved → Ready for Pickup → Completed. The screen also shows the Reference Code, date/time, and purpose. You will also receive an email alert when your document is approved.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How do I edit my profile, change my password, or log out?',
                'answer' => 'In the My Profile screen, you can update your personal information, change your password for security, or log out from the app.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'What are notifications and how do they work?',
                'answer' => 'You will receive in-app alerts via the bell icon for barangay announcements and concern updates. Document approvals are sent to your registered email, not through the bell.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'Can I recover my account if I forget my password?',
                'answer' => 'Yes, tap “Forgot Password?” on the login screen and follow the instructions to reset your password.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'How do I know about upcoming barangay events?',
                'answer' => 'Check the Home/Dashboard feed or the Barangay Events section for official updates on community activities.',
                'category' => 'How to use the app',
            ],
            [
                'question' => 'Is my personal information safe in the app?',
                'answer' => 'Yes, all data is securely stored and only accessed by authorized municipal officials for official purposes.',
                'category' => 'How to use the app',
            ],
        ];

        // Loop through each barangay and insert all FAQs
        foreach ($barangayIds as $bId) {
            foreach ($faqs as $faq) {
                Faq::create(array_merge($faq, ['barangay_id' => $bId]));
            }
        }
    }
}
