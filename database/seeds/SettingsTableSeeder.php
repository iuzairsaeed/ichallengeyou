<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
            [
                'name' => 'CURRENCY',
                'value' => 'USD',
                'type' => 'text',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'PREMIUM_COST',
                'value' => 1,
                'type' => 'number',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'TERMS_CONDITIONS',
                'value' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum scelerisque dignissim iaculis. Etiam quis tempor metus, in facilisis urna. Fusce sit amet consequat est.</p>
                            <ul>
                                <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                                <li>Curabitur semper purus et tempor interdum.</li>
                                <li>Proin suscipit nibh quis ex tincidunt, in pellentesque urna faucibus.</li>
                            </ul>
                            <ul>
                                <li>Quisque sit amet orci molestie, sodales sem eu, mattis quam.</li>
                                <li>Proin ac odio euismod, elementum ligula in, lobortis ante.</li>
                                <li>Donec id turpis maximus, sollicitudin neque egestas, venenatis enim.</li>
                                <li>Pellentesque fermentum risus ultrices tempus porttitor.</li>
                                <li>Nam sit amet purus varius nisi mollis efficitur.</li>
                                <li>Nullam porta nisl a diam accumsan, vitae maximus neque elementum.</li>
                            </ul>',
                'type' => 'textarea',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'PAYPAL_CLIENT_ID',
                'value' => 'Ab53_-WMeShRUNx6lvevKNgqpJYIvM46DRTAuuTzIN_l2QXBPi4d11xgkVRn67bspowmWc6WFClmTv_N',
                'type' => 'text',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'PAYPAL_CLIENT_SECRET',
                'value' => 'EEGikJpagcxHKy6abaKbDN1VgpS61ERo4owIbrlvumcFHlQIYayFZvD9OQnYZmUojNpOjcblDfUyj3Ge',
                'type' => 'text',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'ASK_CANDIDATES_DURATION_IN_HOURS',
                'value' => 24,
                'type' => 'number',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'SECOND_VOTE_DURATION_IN_DAYS',
                'value' => 2,
                'type' => 'number',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'WINNING_AMOUNT_IN_PERCENTAGE',
                'value' => 75,
                'type' => 'number',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'CREATER_AMOUNT_IN_PERCENTAGE',
                'value' => 15,
                'type' => 'number',
                'setting_type' => 'setting'
            ],
            [
                'name' => 'PREMIUM_USER_MESSAGE',
                'value' => 'It\'s 1 USD for god sake. Don’t be so cheap!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'TIMEOUT_MESSAGE',
                'value' => 'You are out of time!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGE_CREATED_MESSAGE',
                'value' => 'Challenge has been created & It will be reviewed and once its approved it will be seen on the time-line',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGE_AMOUNT_MESSAGE',
                'value' => 'Not Enough Balance',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGE_CANNOT_EDIT_MESSAGE',
                'value' => 'You cannot edit your challenge, It has been approved by Admin!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGE_UPDATE_MESSAGE',
                'value' => 'Challenge has been Updated!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGE_CANNOT_DONATE_MESSAGE',
                'value' => 'Donation amount cannot be greater than current account balance.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CANNOT_ACCEPT_DONATOR_MESSAGE',
                'value' => 'You\'re Donator! You Can\'t Accept This Challenge!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CANNOT_ACCEPT_OWN_CHALLENGE_MESSAGE',
                'value' => 'You Can\'t Accept Your Own Challenge!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CANNOT_ACCEPT_ALREADY_CHALLENGE_MESSAGE',
                'value' => 'You have Already accepted This Challenge!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'ACCEPT_CHALLENGE_MESSAGE',
                'value' => 'You have successfully accepted the challenge!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'ASK_ADMIN_APPROVAL_MESSAGE',
                'value' => 'Thanks for your Vote',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'LOGIN_CREDENTIAL_MESSAGE',
                'value' => 'These credentials is incorrect.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'LOGIN_DISABLE_MESSAGE',
                'value' => 'Your account has been disabled. Please contact support.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'LOGIN_MESSAGE',
                'value' => 'You have successfully logged in!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'LOGOUT_MESSAGE',
                'value' => 'You have logged out successfully.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'REGISTER_MESSAGE',
                'value' => 'You have registered successfully.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'FORGET_PASSWORD_INCORRECT_EMAIL_MESSAGE',
                'value' => 'No user exists with provided email.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'FORGET_PASSWORD_CORRECT_EMAIL_MESSAGE',
                'value' => 'An email has been sent to your account with new password. (If you cannot find Check in Spam/Junk)',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHANGE_PASSWORD_MESSAGE',
                'value' => 'Password has been updated successfully',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'LOAD_BALANCE_MESSAGE',
                'value' => 'Your Amount has not been credited to your account',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'SUBMIT_CHALLENGE_MESSAGE',
                'value' => 'You have Successfuly Submitted the Challenge!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VIDEO_ADD_MESSAGE',
                'value' => 'Video has been Added!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VIDEO_DELETE_MESSAGE',
                'value' => 'Video is Deleted!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'PROVIDE_EMAIL_IN_WITHDRAWAL_MESSAGE',
                'value' => 'Please Enter an Email',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'PROVIDE_AMOUNT_IN_WITHDRAWAL_MESSAGE',
                'value' => 'Please Enter any Amount',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'INVALID_AMOUNT_IN_WITHDRAWAL_MESSAGE',
                'value' => 'Your withdrwal amount can not be greater than your current Balance.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CHALLENGER_CANNOT_VOTE_MESSAGE',
                'value' => 'Challenger Can\'t Vote!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'CANNOT_VOTE_OWN_CHALLENGE_MESSAGE',
                'value' => 'You can\'t Vote, your own Challenge',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VOTE_CHALLENGE_TYPE_MESSAGE',
                'value' => 'The result of this Challenge is not based on Vote',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'DONATOR_CAN_VOTE_MESSAGE',
                'value' => 'Only Donator can Vote on this Challenge',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'ADMIN_DECIDE_WINNER_MESSAGE',
                'value' => 'Admin will decide the Winner',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'ALREADY_VOTE_MESSAGE',
                'value' => 'You have already voted to challenger!',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VOTE_REMOVED_MESSAGE',
                'value' => 'Your Vote has been removed',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VOTE_CAST_POSITIVE_MESSAGE',
                'value' => 'Your Vote has been casted Positive on this challenge.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'VOTE_CAST_NEGATIVE_MESSAGE',
                'value' => 'Your Vote has been casted Negative on this challenge.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            [
                'name' => 'START_TIME_MESSAGE',
                'value' => 'Challenges minimum starting time must be 24 hours or greater.',
                'type' => 'text',
                'setting_type' => 'dialog'
            ],
            
        ]);
    }
}
