<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddBranchToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->truncate();
        $statement = "INSERT INTO `permissions` VALUES (1,0,'Members','members','Access Members Module'),(2,1,'View members','members.view','View members'),(3,1,'Update members','members.update','Update members'),(4,1,'Delete members','members.delete','Delete members'),(5,1,'Create members','members.create','Add new members'),(6,0,'Events','events','Access events Module'),(7,6,'Create events','events.create','Create events'),(9,6,'Update events','events.update','Update events'),(10,6,'Delete events','events.delete','Delete events'),(11,6,'View events','events.view','View events'),(20,0,'Payroll','payroll','Access Payroll Module'),(21,20,'View Payroll','payroll.view','View Payroll'),(22,20,'Update Payroll','payroll.update','Update Payroll'),(23,20,'Delete Payroll','payroll.delete','Delete Payroll'),(24,20,'Create Payroll','payroll.create','Create Payroll'),(25,0,'Expenses','expenses','Access Expenses Module'),(26,25,'View Expenses','expenses.view','View Expenses'),(27,25,'Create Expenses','expenses.create','Create Expenses'),(28,25,'Update Expenses','expenses.update','Update Expenses'),(29,25,'Delete Expenses','expenses.delete','Delete Expenses'),(30,0,'Other Income','other_income','Access Other Income Module'),(31,30,'View Other Income','other_income.view','View Other income'),(32,30,'Create Other Income','other_income.create','Create other income'),(33,30,'Update Other Income','other_income.update','Update Other Incom'),(34,30,'Delete Other Income','other_income.delete','Delete other income'),(40,0,'Reports','reports','Access Reports Module'),(41,0,'Communication','communication','Access Communication Module'),(42,41,'Create Communication','communication.create','Send Emails & SMS'),(43,41,'Delete Communication','communication.delete','Delete Communication'),(44,0,'Custom Fields','custom_fields','Access Custom Fields Module'),(45,44,'View Custom Fields','custom_fields.view','View Custom fields'),(46,44,'Create Custom Fields','custom_fields.create','Create Custom Fields'),(47,44,'Custom Fields','custom_fields.update','Update Custom Fields'),(48,44,'Delete Custom Fields','custom_fields.delete','Delete Custom Fields'),(49,0,'Users','users','Access Users Module'),(50,49,'View Users','users.view','View Users '),(51,49,'Create Users','users.create','Create users'),(52,49,'Update Users','users.update','Update Users'),(53,49,'Delete Users','users.delete','Delete Users'),(54,49,'Manage Roles','users.roles','Manage user roles'),(55,0,'Settings','settings','Manage Settings'),(56,0,'Audit Trail','audit_trail','Access Audit Trail'),(74,0,'Dashboard','dashboard','Access Dashboard'),(97,0,'Assets','assets','Access Assets Menu'),(98,97,'Create Assets','assets.create',''),(99,97,'View Assets','assets.view',''),(100,97,'Update Assets','assets.update',''),(101,97,'Delete Assets','assets.delete',''),(102,0,'Tags','tags','Access Tags menu'),(103,102,'View Tags','tags.view',''),(104,102,'Create Tags','tags.create',''),(105,102,'Update Tags','tags.update',''),(106,102,'Delete Tags','tags.delete',''),(107,0,'Follow Ups','follow_ups','Access Follow Ups'),(108,107,'View Follow Ups','follow_ups.view',''),(109,107,'Create Follow Ups','follow_ups.create',''),(110,107,'Update Follow Ups','follow_ups.update',''),(111,107,'Delete Follow Ups','follow_ups.delete',''),(112,0,'Pledges','pledges',''),(113,112,'View Pledges','pledges.view',''),(114,112,'Create Pledges','pledges.create',''),(115,112,'Update Pledges','pledges.update',''),(116,112,'Delete Pledges','pledges.delete',''),(117,0,'Contributions','contributions','Access Contributions Menu'),(118,117,'View Contributions','contributions.view',''),(119,117,'Create Contributions','contributions.create',''),(120,117,'Update Contributions','contributions.update',''),(121,117,'Delete Contributions','contributions.delete',''),(122,74,'Member Statistics','dashboard.members_statistics',''),(123,74,'Events Calendar','dashboard.calendar',''),(124,74,'Contributions','dashboard.contributions_statistics',''),(125,74,'Pledges Statistics','dashboard.pledges_statistics',''),(126,74,'Finance Graph','dashboard.finance_graph',''),(127,74,'Tags Statistics','dashboard.tags_statistics',''),(128,0,'Branches','branches',''),(129,128,'View Branches','branches.view',''),(130,128,'Create Branches','branches.create',''),(131,128,'Edit Branches','branches.update',''),(132,128,'Delete Branches','branches.delete',''),(133,128,'Access All Branches','branches.view_all','');";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
