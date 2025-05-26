<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">

  # 🎯 Trello Clone
  
  [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![Livewire](https://img.shields.io/badge/Livewire-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)](https://laravel-livewire.com)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black)](https://alpinejs.dev)
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![Node.js](https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)](https://nodejs.org)
  [![NPM](https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white)](https://www.npmjs.com)
</div>

A modern project management application inspired by Trello, built with Laravel and Livewire. Manage your projects with an intuitive drag-and-drop interface and real-time updates.

## ✨ Features

- 📋 **Project Management**
  - Create and manage multiple projects
  - Organize tasks with customizable lists
  - Drag-and-drop task management
  
- 👥 **Team Collaboration**
  - Invite team members
  - Assign tasks to team members
  - Real-time activity updates
  
- 💬 **Communication**
  - Comment on tasks
  - Attach files to tasks
  - Track task history
  
- 🚀 **Real-time Updates**
  - Live task updates using Laravel Echo
  - Instant notifications
  - Activity logging

## 🛠️ Tech Stack

<div align="center">

### Backend
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Redis](https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white)](https://redis.io)

### Frontend
[![Livewire](https://img.shields.io/badge/Livewire-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)](https://laravel-livewire.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black)](https://alpinejs.dev)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

### Development Tools
[![VS Code](https://img.shields.io/badge/VS_Code-007ACC?style=for-the-badge&logo=visual-studio-code&logoColor=white)](https://code.visualstudio.com)
[![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)](https://git-scm.com)
[![NPM](https://img.shields.io/badge/npm-CB3837?style=for-the-badge&logo=npm&logoColor=white)](https://www.npmjs.com)
[![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)](https://getcomposer.org)

</div>

## ⚙️ Requirements

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL

## 🚀 Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/trello-clone.git
cd trello-clone
```

2. Install PHP dependencies
```bash
composer install
```

3. Install & compile frontend assets
```bash
npm install
npm run dev
```

4. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trello_clone
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations
```bash
php artisan migrate
```

7. Start the development server
```bash
php artisan serve
```

## 🎯 Usage Guide

1. Register a new account
2. Create a team
3. Create a new project board
4. Add lists to organize your tasks
5. Create and manage tasks
6. Invite team members to collaborate

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the MIT license.

## 🙏 Acknowledgments

- Inspired by [Trello](https://trello.com)
- Built with [Laravel](https://laravel.com)
- UI components from [Tailwind CSS](https://tailwindcss.com)

---
<div align="center">
  <sub>Made with ❤️ by Shivam Singh Parihar</sub>
  
  [![GitHub stars](https://img.shields.io/github/stars/yourusername/trello-clone?style=social)](https://github.com/Shivam-Singh-Parihar/trello-clone)
  [![Follow me](https://img.shields.io/github/followers/yourusername?style=social)](https://github.com/Shivam-Singh-Parihar)
</div>
