# QPOS — Complete QA Checklist
**Version:** Commercial Release 1.0  
**Date:** 2026-06-23

---

## SMOKE TEST CHECKLIST (Run after every deployment)

### Authentication
- [ ] Login with valid credentials succeeds
- [ ] Login with wrong password fails with correct error message
- [ ] Failed login is recorded in Audit Logs
- [ ] Logout works and redirects to login page
- [ ] Logout is recorded in Audit Logs
- [ ] Password reset via OTP email works end-to-end
- [ ] Google OAuth login works
- [ ] Suspended user cannot login
- [ ] Session expires correctly after inactivity

### Dashboard
- [ ] Dashboard loads without error
- [ ] All stat cards show correct numbers
- [ ] Charts render correctly
- [ ] Notification bell shows unread count
- [ ] Notification dropdown shows recent alerts

### POS / Sales
- [ ] POS cart page loads
- [ ] Products load in POS search
- [ ] Adding product to cart works
- [ ] Quantity increment/decrement works
- [ ] Removing item from cart works
- [ ] Cart total calculates correctly
- [ ] Cash sale creates order correctly
- [ ] Installment sale creates order and plan correctly
- [ ] Invoice generates correctly after sale
- [ ] Stock quantity decreases after sale
- [ ] Stock movement recorded for sale

### Products
- [ ] Product list loads with search
- [ ] Product create with image works
- [ ] Product edit works
- [ ] Product delete works
- [ ] Low stock threshold field saves correctly
- [ ] Product import (Excel) works

### Customers & Suppliers
- [ ] Customer create, edit, delete works
- [ ] Customer history (orders) loads
- [ ] Guarantor add/view works
- [ ] Supplier CRUD works

### Installments
- [ ] Installment plans list loads
- [ ] Installment plan detail shows schedules
- [ ] Installment payment collection updates schedule status
- [ ] Overdue installments show in dashboard
- [ ] Due today installments show correctly
- [ ] Upcoming installments load

### Reports
- [ ] Sales Summary report generates
- [ ] Sales Detail report filters by date
- [ ] Inventory report loads with DataTable
- [ ] Sales by Day report works
- [ ] Sales by Month report works
- [ ] Sales by Product report works
- [ ] Sales by Employee report works
- [ ] Installment Collections report works
- [ ] Outstanding Balances report works

### Backup System
- [ ] Manual backup runs and shows in history
- [ ] Backup status shows "success"
- [ ] Backup file can be downloaded
- [ ] Backup health status is accurate
- [ ] Backup delete works

### Audit Logs
- [ ] Audit logs list loads with DataTable
- [ ] Login events appear in logs
- [ ] Logout events appear in logs
- [ ] Payment events appear in logs
- [ ] Sale creation appears in logs
- [ ] Filters work (by action, module, user, date)

### Notifications
- [ ] Notification center loads
- [ ] Mark as read works
- [ ] Mark all as read works
- [ ] Delete notification works
- [ ] Unread count updates in navbar

### Stock Movements
- [ ] Stock movements list loads
- [ ] Manual adjustment creates movement record
- [ ] Filters work (by product, type, date)
- [ ] Low stock alert created when stock hits threshold

### Cash Register
- [ ] Open cash register works
- [ ] Close cash register calculates variance
- [ ] Register history loads

### System Health
- [ ] Health check page loads
- [ ] Database check shows "ok"
- [ ] Storage check shows "ok"
- [ ] Folder check shows "ok"

### License
- [ ] License page loads
- [ ] Store information saves correctly
- [ ] License key and expiry save

### Settings
- [ ] General website settings save
- [ ] Invoice settings save
- [ ] Role creation works
- [ ] Role-permission assignment works
- [ ] User creation with role assignment works
- [ ] Currency management works

---

## BACKUP/RESTORE TEST SCENARIOS

### Scenario 1: Manual Backup
1. Go to Backup & Restore page
2. Select "Manual" type
3. Click "Run Backup"
4. Verify backup appears in history with "Success" status
5. Check backup size is > 0
6. Download backup and verify file is not empty SQL

### Scenario 2: Automatic Backup (requires scheduler running)
1. Enable Laravel scheduler: `php artisan schedule:work`
2. Wait for the next hour
3. Verify "hourly" backup appears in history
4. Verify daily backup appears after 02:00 AM

### Scenario 3: Restore from Backup
1. Create a test customer with name "RESTORE_TEST_123"
2. Run a manual backup
3. Delete the test customer
4. Go to Backup & Restore
5. Click Restore on the backup just created
6. Type "RESTORE" in confirmation field
7. Verify test customer reappears
8. Verify restore is logged in Audit Logs

### Scenario 4: Backup Health Alerts
1. Temporarily change last backup time to >25 hours ago
2. Go to health page — verify "Warning" status shows
3. Delete all backups — verify "Critical" status
4. Run new backup — verify status returns to "Healthy"

### Scenario 5: Backup Cleanup
1. Verify old backups (>30 days) are cleaned by scheduler
2. Manually run: `php artisan backup:cleanup --days=30`
3. Verify old backups are removed

---

## INSTALLMENT TEST SCENARIOS

### Scenario 1: Create Installment Sale
1. Add products to POS cart
2. Select "Installment" as sale type
3. Select customer with guarantor
4. Enter 6 months, down payment
5. Complete sale
6. Verify installment plan created with 6 schedules
7. Verify each schedule has correct due date and amount
8. Verify stock decreased correctly

### Scenario 2: Monthly Payment Collection
1. Open existing installment order
2. Click "Due Collection"
3. Enter payment amount
4. Verify schedule status changes to "paid" or "partial"
5. Verify payment allocation created
6. Verify order "paid" and "due" amounts updated
7. Verify audit log shows payment

### Scenario 3: Overdue Detection
1. Set an installment schedule's due_date to yesterday
2. Run: `php artisan installments:check-overdue`
3. Verify schedule status changes to "overdue"
4. Verify notification created in system
5. Verify Installment Dashboard shows overdue count

### Scenario 4: Completed Plan
1. Make all payments for an installment plan
2. Verify plan status changes to "completed"
3. Verify order status changes to "paid"
4. Verify no more overdue alerts for this plan

### Scenario 5: Outstanding Balances Report
1. Go to Reports > Advanced > Outstanding Balances
2. Verify all active plans show with correct remaining amounts
3. Verify total outstanding amount is accurate

---

## INVENTORY TEST SCENARIOS

### Scenario 1: Stock Movement on Sale
1. Note product stock before sale (e.g., 50)
2. Complete POS sale with 3 units of that product
3. Verify product stock is now 47
4. Go to Stock Movements
5. Verify "sale" movement recorded: before=50, change=-3, after=47

### Scenario 2: Stock Movement on Purchase
1. Note product stock before purchase (e.g., 47)
2. Create purchase order with 20 units
3. Verify product stock is now 67
4. Go to Stock Movements
5. Verify "purchase" movement: before=47, change=+20, after=67

### Scenario 3: Manual Adjustment
1. Go to Stock Movements page
2. Select product, enter adjustment of -5
3. Select reason "Physical count correction"
4. Verify stock decreased by 5
5. Verify movement recorded with type "adjustment"
6. Verify audit log shows inventory_adjustment event

### Scenario 4: Low Stock Alert
1. Set product low_stock_threshold = 10
2. Adjust stock so it goes to 8
3. Verify system notification created for "low_stock"
4. Verify notification appears in notification center
5. Verify notification bell count increases

### Scenario 5: Negative Stock Prevention
1. Try to sell more units than available (if enabled)
2. Verify error message prevents the sale
3. Verify stock does not go negative

---

## AUDIT LOG TEST SCENARIOS

### Verify All Events Are Captured:

| Event | Action to Trigger | Expected Log Entry |
|-------|------------------|--------------------|
| Login | Log in with valid credentials | action=login, module=auth |
| Failed Login | Enter wrong password | action=failed_login, module=auth |
| Logout | Click logout | action=logout, module=auth |
| Sale Create | Complete POS sale | action=create, module=orders |
| Payment | Collect due payment | action=payment, module=orders |
| Installment Change | Install. plan updated | action=installment_change |
| Inventory Adj | Adjust stock manually | action=inventory_adjustment |
| Backup Restore | Restore from backup | action=restore, module=backup |

---

## CASH REGISTER TEST SCENARIOS

### Scenario 1: Open Register
1. Go to Cash Register
2. Enter opening cash: 5000
3. Click "Open Cash Register"
4. Verify register shows as "open"

### Scenario 2: Close Register with Cash Transactions
1. Make 2 cash sales totaling 2500
2. Go to Cash Register
3. Enter actual closing cash (e.g., 7400)
4. Expected = 5000 + 2500 = 7500
5. Variance = 7400 - 7500 = -100 (deficit)
6. Verify variance shows in red

### Scenario 3: Balanced Register
1. Ensure no cash transactions occurred
2. Close with same amount as opening
3. Verify variance = 0, shows "Balanced"

---

## PRODUCTION DEPLOYMENT CHECKLIST

### Pre-Deployment
- [ ] All migrations run: `php artisan migrate`
- [ ] Storage linked: `php artisan storage:link`
- [ ] Cache cleared: `php artisan optimize:clear`
- [ ] .env configured with production values
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_KEY set (not empty)
- [ ] DB credentials correct and tested
- [ ] Mail configured and tested
- [ ] Backup folder exists: `storage/app/backups/`
- [ ] Folder permissions set (775 on storage/, bootstrap/cache/)

### Scheduler Setup (Required for auto backups)
Add to crontab:
```
* * * * * cd /path/to/pos && php artisan schedule:run >> /dev/null 2>&1
```

### Post-Deployment
- [ ] Login works
- [ ] Dashboard loads
- [ ] Create test sale end-to-end
- [ ] Run manual backup
- [ ] Verify system health: all checks pass
- [ ] Check notification center: no failed alerts
- [ ] Set up license information
- [ ] Configure store details in License page
- [ ] Review Backup & Restore schedule is active
- [ ] Create admin user and test role permissions

### Security Checklist
- [ ] Strong passwords enforced
- [ ] Failed login attempts logged
- [ ] Audit logs accessible to admin only
- [ ] Backup files in protected storage (not public)
- [ ] CSRF protection enabled (default in Laravel)
- [ ] Session secure flags set for HTTPS
- [ ] No debug routes exposed in production

---

## COMMERCIAL READINESS SCORE

See `docs/COMMERCIAL_AUDIT_REPORT.md` for full scoring breakdown.
