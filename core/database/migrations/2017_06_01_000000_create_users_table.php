<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('resellers', function(Blueprint $table)
    {
      $table->increments('id');
      $table->string('api_token', 60)->nullable()->unique();
      $table->string('name', 32);
      $table->string('domain', 250);
      $table->string('phone', 16)->nullable();
      $table->string('support_email', 64)->nullable();
      $table->string('default_language', 5)->default('en');
      $table->string('default_locale', 5)->default('en');
      $table->string('default_timezone', 32)->default('UTC');
      $table->char('default_currency_code', 3)->default('USD');
      $table->char('default_country_code', 2)->nullable();
      $table->string('mail_from_address', 64)->nullable();
      $table->string('mail_from_name', 64)->nullable();
      $table->string('mail_driver', 32)->nullable();
      $table->string('mail_host', 150)->nullable();
      $table->string('mail_port', 5)->nullable();
      $table->string('mail_encryption', 5)->nullable();
      $table->text('mail_mailgun_domain')->nullable();
      $table->text('mail_mailgun_secret')->nullable();
      $table->text('mail_username')->nullable();
      $table->text('mail_password')->nullable();
      $table->string('page_title', 250)->nullable();
      $table->string('favicon', 250)->nullable();
      $table->string('logo', 250)->nullable();
      $table->string('logo_square', 250)->nullable();
      $table->string('ga_tracking_code', 24)->nullable();
      $table->boolean('avangate_sandbox')->default(false);
      $table->text('avangate_key')->nullable();
      $table->string('avangate_affiliate', 64)->nullable();
      $table->boolean('stripe_sandbox')->default(false);
      $table->text('stripe_key')->nullable();
      $table->text('stripe_secret')->nullable();
      $table->boolean('braintree_sandbox')->default(false);
      $table->text('braintree_merchant_id')->nullable();
      $table->text('braintree_public_key')->nullable();
      $table->text('braintree_private_key')->nullable();
      $table->boolean('active')->default(true);
      $table->json('settings')->nullable();
      $table->dateTime('expires')->nullable();
      $table->timestamps();
    });

    Schema::create('plans', function ($table) {
      $table->increments('id');
      $table->integer('order');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade');
      $table->string('name', 64);
      $table->text('description')->nullable();
      $table->string('ribbon', 64)->nullable();
      $table->integer('trial_days')->unsigned()->nullable();
      $table->char('currency', 3);
      $table->string('monthly_price', 24);
      $table->json('monthly_price_currencies')->nullable();
      $table->string('monthly_remote_product_id', 64)->nullable();
      $table->text('monthly_order_url')->nullable();
      $table->text('monthly_upgrade_url')->nullable();
      $table->string('annual_price', 24)->nullable();
      $table->json('annual_price_currencies')->nullable();
      $table->string('annual_remote_product_id', 64)->nullable();
      $table->text('annual_order_url')->nullable();
      $table->text('annual_upgrade_url')->nullable();
      $table->json('limitations')->nullable();
      $table->json('extra')->nullable();
      $table->boolean('active')->default(true);
      $table->boolean('default')->default(false);
      $table->timestamps();
    });

    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('remote_id', 64)->nullable();
      $table->integer('is_reseller_id')->unsigned()->nullable();
      $table->foreign('is_reseller_id')->references('id')->on('resellers')->onDelete('set null');
      $table->integer('reseller_id')->unsigned()->nullable();
      $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('set null');
      $table->integer('parent_id')->unsigned()->nullable();
      $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
      $table->integer('plan_id')->unsigned()->nullable();
      $table->foreign('plan_id')->references('id')->on('plans');
      $table->string('role', 20)->default('user');
      $table->string('name', 64);
      $table->string('phone', 16)->nullable();
      $table->string('email');
      $table->string('password', 60)->nullable();
      $table->string('api_token', 60)->nullable()->unique();
      $table->boolean('active')->default(true);
      $table->boolean('confirmed')->default(false);
      $table->string('confirmation_code')->nullable();
      $table->string('language', 5)->default('en');
      $table->char('country_code', 2)->nullable();
      $table->string('locale', 5)->default('en');
      $table->string('timezone', 32)->default('UTC');
      $table->char('currency_code', 3)->default('USD');
      $table->integer('logins')->default(0)->unsigned();
      $table->ipAddress('last_ip')->nullable();
      $table->timestamp('expires')->nullable();
      $table->tinyInteger('expires_reminders_sent')->default(0)->unsigned();
      $table->dateTime('last_login')->nullable();
      $table->json('settings')->nullable();

      // Avatar
      $table->string('avatar_file_name')->nullable();
      $table->integer('avatar_file_size')->nullable();
      $table->string('avatar_content_type')->nullable();
      $table->timestamp('avatar_updated_at')->nullable();

      // Stripe
      $table->string('stripe_id')->nullable();

      // Braintree
      $table->string('braintree_id')->nullable();
      $table->string('paypal_email')->nullable();

      // General
      $table->string('card_brand')->nullable();
      $table->string('card_last_four')->nullable();
      $table->timestamp('trial_ends_at')->nullable();
      $table->tinyInteger('trial_ends_reminders_sent')->default(0)->unsigned();

      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_resets', function (Blueprint $table) {
      $table->string('email')->index();
      $table->string('token')->index();
      $table->timestamps();
    });

    Schema::create('subscriptions', function ($table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name');

      // Avangate
      $table->string('avangate_id')->nullable();
      $table->string('avangate_plan')->nullable();

      // Stripe
      $table->string('stripe_id')->nullable();
      $table->string('stripe_plan')->nullable();

      // Braintree
      $table->string('braintree_id')->nullable();
      $table->string('braintree_plan')->nullable();

      $table->timestamp('trial_ends_at')->nullable();
      $table->timestamp('ends_at')->nullable();
      $table->json('settings')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('subscriptions');
    Schema::drop('password_resets');
    Schema::drop('users');
    Schema::drop('plans');
    Schema::drop('resellers');
  }
}
