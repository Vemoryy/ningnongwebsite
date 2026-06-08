# NingNong Admin Panel Testing Plan

## 📋 Overview
This document outlines the comprehensive testing plan for the NingNong Admin Panel functionality. The admin panel allows authorized users to manage products including adding, editing, deleting, and toggling product visibility.

---

## 🔐 Test Environment

| Component | Details |
|-----------|---------|
| Server | XAMPP (Apache + MySQL) |
| PHP Version | 7.4+ |
| Database | ningnong_db |
| Base URL | http://localhost/NingNong/pages/ |
| Browser | Chrome/Firefox/Edge |

### Test Accounts

| Email | Password | Role |
|-------|----------|------|
| ningnong@gmail.com | password | admin |
| ken@gmail.com | password | admin |
| leon@gmail.com | password | admin (test user) |

---

## 🧪 Test Cases

### 1. Authentication & Authorization

#### TC-1.1: Admin Login
- **Precondition**: User is not logged in
- **Steps**:
  1. Navigate to `http://localhost/NingNong/pages/login.php`
  2. Enter admin email: `leon@gmail.com`
  3. Enter password: `password`
  4. Click "Sign In"
- **Expected Result**: User is redirected to dashboard with "Admin Panel" link visible
- **Status**: ⬜ Not Tested

#### TC-1.2: Access Admin Panel
- **Precondition**: Logged in as admin
- **Steps**:
  1. Click "Admin Panel" link in dashboard sidebar
  2. OR navigate to `http://localhost/NingNong/pages/admin_products.php`
- **Expected Result**: Admin Products page loads with product table and stats
- **Status**: ⬜ Not Tested

#### TC-1.3: Non-Admin Access Denied
- **Precondition**: Logged in as regular user (not admin)
- **Steps**:
  1. Navigate directly to `http://localhost/NingNong/pages/admin_products.php`
- **Expected Result**: User is redirected to dashboard or shown "Access Denied"
- **Status**: ⬜ Not Tested

---

### 2. Product Listing

#### TC-2.1: View All Products
- **Precondition**: Logged in as admin, on admin panel
- **Steps**:
  1. View the products table
- **Expected Result**: All products displayed with ID, Image, Name, Category, Price, Featured, Active, Actions
- **Status**: ⬜ Not Tested

#### TC-2.2: Stats Cards Accuracy
- **Precondition**: On admin panel page
- **Steps**:
  1. Count products in table
  2. Compare with stats cards
- **Expected Result**: Stats cards show correct Total, Active, Featured, and Categories counts
- **Status**: ⬜ Not Tested

---

### 3. Add Product

#### TC-3.1: Add Product Without Image
- **Precondition**: On admin panel page
- **Steps**:
  1. Click "Add New Product" button
  2. Fill in:
     - Name (EN): "Test Cookies"
     - Name (ID): "Kue Test"
     - Description (EN): "Delicious test cookies"
     - Description (ID): "Kue test lezat"
     - Price: 25000
     - Category: snack
     - Leave image empty
  3. Click "Add Product"
- **Expected Result**: Product added, success message shown, product appears in table with default image
- **Status**: ⬜ Not Tested

#### TC-3.2: Add Product With Image
- **Precondition**: On admin panel page
- **Steps**:
  1. Click "Add New Product" button
  2. Fill in all fields
  3. Select an image file (JPG/PNG under 5MB)
  4. Click "Add Product"
- **Expected Result**: Product added with uploaded image, image displayed in table
- **Status**: ⬜ Not Tested

#### TC-3.3: Add Product - Validation (Empty Required Fields)
- **Precondition**: On admin panel page
- **Steps**:
  1. Click "Add New Product"
  2. Leave Name (EN) empty
  3. Click "Add Product"
- **Expected Result**: Error message shown, product not added
- **Status**: ⬜ Not Tested

---

### 4. Edit Product

#### TC-4.1: Edit Product Without Changing Image
- **Precondition**: At least one product exists
- **Steps**:
  1. Click "Edit" button on a product
  2. Change the price from current to 30000
  3. Click "Update Product"
- **Expected Result**: Product updated, success message, new price shown in table
- **Status**: ⬜ Not Tested

#### TC-4.2: Edit Product With New Image
- **Precondition**: At least one product exists
- **Steps**:
  1. Click "Edit" button on a product
  2. Select a new image file
  3. Click "Update Product"
- **Expected Result**: Product updated with new image, old image replaced
- **Status**: ⬜ Not Tested

#### TC-4.3: Edit Product Modal Pre-populated
- **Precondition**: At least one product exists
- **Steps**:
  1. Click "Edit" button on a product
- **Expected Result**: Modal opens with all existing product data pre-filled
- **Status**: ⬜ Not Tested

---

### 5. Delete Product

#### TC-5.1: Delete Product Confirmation
- **Precondition**: At least one product exists
- **Steps**:
  1. Click "Delete" button on a product
- **Expected Result**: Confirmation dialog appears asking "Are you sure?"
- **Status**: ⬜ Not Tested

#### TC-5.2: Delete Product - Confirm
- **Precondition**: Delete confirmation shown
- **Steps**:
  1. Click "OK" on confirmation
- **Expected Result**: Product removed from table, success message shown
- **Status**: ⬜ Not Tested

#### TC-5.3: Delete Product - Cancel
- **Precondition**: Delete confirmation shown
- **Steps**:
  1. Click "Cancel" on confirmation
- **Expected Result**: Product NOT deleted, remains in table
- **Status**: ⬜ Not Tested

---

### 6. Toggle Features

#### TC-6.1: Toggle Featured Status
- **Precondition**: Product exists with Featured = "No"
- **Steps**:
  1. Click "Featured" toggle button (star icon)
- **Expected Result**: Status changes to "Yes", icon changes to filled star
- **Status**: ⬜ Not Tested

#### TC-6.2: Toggle Active Status
- **Precondition**: Product exists with Active = "Yes"
- **Steps**:
  1. Click "Active" toggle button (check icon)
- **Expected Result**: Status changes to "No", product no longer visible on public pages
- **Status**: ⬜ Not Tested

---

### 7. Image Upload

#### TC-7.1: Valid Image Upload (JPG)
- **Precondition**: Adding/editing product
- **Steps**:
  1. Select a .jpg file under 5MB
  2. Submit form
- **Expected Result**: Image uploaded to /Image/ folder, displayed correctly
- **Status**: ⬜ Not Tested

#### TC-7.2: Valid Image Upload (PNG)
- **Precondition**: Adding/editing product
- **Steps**:
  1. Select a .png file under 5MB
  2. Submit form
- **Expected Result**: Image uploaded successfully
- **Status**: ⬜ Not Tested

#### TC-7.3: Invalid File Type
- **Precondition**: Adding/editing product
- **Steps**:
  1. Select a .txt or .exe file
  2. Submit form
- **Expected Result**: Error message: "Only JPG, JPEG, PNG & GIF files allowed"
- **Status**: ⬜ Not Tested

#### TC-7.4: File Too Large
- **Precondition**: Adding/editing product
- **Steps**:
  1. Select an image file over 5MB
  2. Submit form
- **Expected Result**: Error message: "File too large"
- **Status**: ⬜ Not Tested

---

### 8. Integration Tests

#### TC-8.1: Added Product Appears on Products Page
- **Precondition**: New product added via admin panel
- **Steps**:
  1. Navigate to `http://localhost/NingNong/pages/products.php`
- **Expected Result**: New product visible in product listing
- **Status**: ⬜ Not Tested

#### TC-8.2: Featured Product Appears on Homepage
- **Precondition**: Product marked as featured
- **Steps**:
  1. Navigate to `http://localhost/NingNong/pages/index.php`
- **Expected Result**: Product visible in "Featured Products" section
- **Status**: ⬜ Not Tested

#### TC-8.3: Inactive Product Hidden from Public
- **Precondition**: Product marked as inactive
- **Steps**:
  1. Navigate to products page as guest
- **Expected Result**: Inactive product NOT visible
- **Status**: ⬜ Not Tested

---

## 📊 Test Execution Log

| Test ID | Date | Tester | Result | Notes |
|---------|------|--------|--------|-------|
| TC-1.1 | | | | |
| TC-1.2 | | | | |
| TC-1.3 | | | | |
| TC-2.1 | | | | |
| TC-2.2 | | | | |
| TC-3.1 | | | | |
| TC-3.2 | | | | |
| TC-3.3 | | | | |
| TC-4.1 | | | | |
| TC-4.2 | | | | |
| TC-4.3 | | | | |
| TC-5.1 | | | | |
| TC-5.2 | | | | |
| TC-5.3 | | | | |
| TC-6.1 | | | | |
| TC-6.2 | | | | |
| TC-7.1 | | | | |
| TC-7.2 | | | | |
| TC-7.3 | | | | |
| TC-7.4 | | | | |
| TC-8.1 | | | | |
| TC-8.2 | | | | |
| TC-8.3 | | | | |

---

## 🐛 Bug Report Template

### Bug #[NUMBER]
- **Test Case**: TC-X.X
- **Severity**: Critical / High / Medium / Low
- **Description**: 
- **Steps to Reproduce**:
- **Expected Result**:
- **Actual Result**:
- **Screenshot**: 
- **Status**: Open / In Progress / Fixed / Closed

---

## ✅ Sign-Off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Tester | | | |
| Developer | | | |
| Project Owner | | | |

---

## 📝 Notes

1. Ensure XAMPP Apache and MySQL services are running before testing
2. Clear browser cache between tests if experiencing issues
3. Check PHP error logs at `C:\xampp\php\logs\php_error_log` for debugging
4. Image upload directory must have write permissions

---

*Document Version: 1.0*  
*Created: January 2025*  
*Project: NingNong Indonesia Website*
