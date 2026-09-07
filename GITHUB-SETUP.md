# GitHub Repository Setup Guide

## 📝 Steps to Connect Your Project to GitHub

### Step 1: Create GitHub Repository

1. **Go to GitHub**: https://github.com
2. **Sign in** to your GitHub account
3. **Click** the "+" icon in the top right corner
4. **Select** "New repository"

### Step 2: Configure Repository Settings

Fill in the repository details:

**Repository Name**: `unisiraj-attendance-system`
- Or any name you prefer (use lowercase and hyphens)

**Description** (optional but recommended):
```
🎓 UniSIRAJ Automated Attendance Management System - QR-based attendance with advanced analytics and ML-inspired features. Production-ready system with 98% test pass rate.
```

**Visibility**:
- ✅ **Public** - If you want to showcase your work
- ⚪ **Private** - If you want to keep it private

**Initialize repository**:
- ⚪ Do NOT check "Add a README file"
- ⚪ Do NOT check "Add .gitignore"
- ⚪ Do NOT choose a license yet

**Click** "Create repository"

---

### Step 3: Connect Local Repository to GitHub

After creating the repository, GitHub will show you commands. Use these:

#### Option A: If you see "Quick setup" page

Copy your repository URL (it will look like):
```
https://github.com/YOUR-USERNAME/unisiraj-attendance-system.git
```

Then run these commands in your project folder:

```bash
# Add remote repository
git remote add origin https://github.com/YOUR-USERNAME/unisiraj-attendance-system.git

# Verify remote was added
git remote -v

# Push to GitHub (main branch)
git branch -M main
git push -u origin main
```

#### Option B: Alternative HTTPS method

```bash
git remote add origin https://github.com/YOUR-USERNAME/unisiraj-attendance-system.git
git push -u origin master
```

---

### Step 4: Authenticate with GitHub

When you push for the first time, Windows will ask for authentication:

**Option 1: Personal Access Token (Recommended)**

1. Go to GitHub → Settings → Developer settings → Personal access tokens
2. Click "Generate new token (classic)"
3. Give it a name: "UniSIRAJ Attendance System"
4. Select scopes: ✅ repo (full control)
5. Click "Generate token"
6. **Copy the token** (you won't see it again!)
7. When prompted for password, paste the token

**Option 2: GitHub Desktop**

If commands don't work, you can use GitHub Desktop:
1. Download from: https://desktop.github.com/
2. Install and sign in
3. Add existing repository
4. Publish to GitHub

---

### Step 5: Verify Upload

After pushing, go to your GitHub repository URL in browser.

You should see:
- ✅ All your project files
- ✅ README.md displayed
- ✅ Documentation folder
- ✅ Source code

---

## 🔒 Important Security Notes

### Files NOT Uploaded (Protected by .gitignore):

- ✅ `config/database.php` - Your actual database credentials
- ✅ `*.log` - Log files
- ✅ `.env` - Environment files
- ✅ `public/assets/qr-codes/*.png` - Generated QR codes

### Files Uploaded (Safe):

- ✅ `config/database.example.php` - Template without real credentials
- ✅ All source code
- ✅ Documentation
- ✅ README.md

---

## 📋 Quick Reference Commands

### Check Status
```bash
git status
```

### Add Files
```bash
# Add all files
git add .

# Add specific file
git add filename.php
```

### Commit Changes
```bash
git commit -m "Your commit message here"
```

### Push to GitHub
```bash
# First time
git push -u origin main

# Subsequent pushes
git push
```

### Pull from GitHub
```bash
git pull origin main
```

### View Remote
```bash
git remote -v
```

### Change Remote URL
```bash
git remote set-url origin https://github.com/YOUR-USERNAME/new-repo.git
```

---

## 🎯 Making Future Updates

When you make changes to your project:

```bash
# 1. Check what changed
git status

# 2. Add changes
git add .

# 3. Commit with message
git commit -m "Describe your changes"

# 4. Push to GitHub
git push
```

---

## 🌟 Repository Best Practices

### Good Commit Messages

✅ Good:
- "Add password reset functionality"
- "Fix QR code generation bug"
- "Update README with installation instructions"
- "Improve analytics dashboard performance"

❌ Bad:
- "update"
- "fixes"
- "changes"
- "test"

### Commit Frequency

- Commit after completing a feature
- Commit after fixing a bug
- Commit before making major changes
- Don't commit broken code

### What to Commit

✅ Do commit:
- Source code
- Documentation
- Configuration templates
- README files
- Database schemas

❌ Don't commit:
- Passwords or credentials
- Generated files (QR codes)
- Log files
- Personal notes
- Large binary files

---

## 🎓 Adding Repository Details

### Add Topics (Tags)

On your GitHub repository page:
1. Click "⚙️ Settings"
2. Find "Topics" section
3. Add relevant topics:
   - `php`
   - `mysql`
   - `attendance-system`
   - `qr-code`
   - `education`
   - `analytics`
   - `bootstrap`
   - `chart-js`

### Update README

Make sure your README.md includes:
- ✅ Project description
- ✅ Features list
- ✅ Installation instructions
- ✅ Screenshots
- ✅ Technologies used
- ✅ License information

### Add License

1. Go to repository on GitHub
2. Click "Add file" → "Create new file"
3. Name it `LICENSE`
4. Click "Choose a license template"
5. Select "MIT License" (recommended for academic projects)
6. Fill in your name and year
7. Commit the file

---

## 📸 Adding Screenshots

1. Take screenshots of your system
2. Create folder: `screenshots/`
3. Add images
4. Reference in README.md:

```markdown
![Admin Dashboard](screenshots/admin-dashboard.png)
![QR Generation](screenshots/qr-generation.png)
![Analytics](screenshots/analytics.png)
```

---

## 🏆 Making Your Repository Stand Out

### Add a Banner

Create a banner image for your project and add to README:
```markdown
<div align="center">
  <img src="banner.png" alt="UniSIRAJ Attendance System" width="800"/>
</div>
```

### Add Badges

Add status badges to README.md:
```markdown
![Status](https://img.shields.io/badge/Status-Production%20Ready-success)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple)
![License](https://img.shields.io/badge/License-MIT-green)
```

### Create Project Website (GitHub Pages)

1. Go to repository Settings
2. Scroll to "Pages" section
3. Select source: "Deploy from a branch"
4. Select branch: "main" and folder: "/(root)" or "/docs"
5. Save
6. Your project will be live at: `https://yourusername.github.io/unisiraj-attendance-system/`

---

## 🆘 Troubleshooting

### Problem: "Permission denied"

**Solution**: Use Personal Access Token instead of password

### Problem: "Repository not found"

**Solution**: Check URL spelling and ensure repository exists

### Problem: "Updates were rejected"

**Solution**: 
```bash
git pull origin main --allow-unrelated-histories
git push origin main
```

### Problem: "Large files warning"

**Solution**: Add large files to .gitignore and remove from staging:
```bash
git rm --cached large-file.zip
git commit -m "Remove large file"
```

---

## 📞 Need Help?

- **GitHub Docs**: https://docs.github.com
- **Git Tutorial**: https://git-scm.com/docs/gittutorial
- **GitHub Desktop**: https://desktop.github.com

---

**Good luck with your GitHub repository!** 🚀

Remember: Your first commit is already done, so you just need to:
1. Create repository on GitHub
2. Add remote
3. Push your code

**You're almost there!** 🎉
