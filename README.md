# Carbon Footprint Calculator

A web application for calculating personal carbon footprints with regional comparisons and personalized recommendations.

## ✨ Features

- Multi-step calculator covering transportation, energy, and travel
- Regional and national carbon footprint comparisons  
- Interactive charts and visual analytics
- Personalized reduction recommendations
- Secure, mobile-friendly design

## 🚀 Quick Start

1. **Setup Database**
   ```bash
   mysql -u root -p -e "CREATE DATABASE carbon_footprint_calc;"
   mysql -u root -p carbon_footprint_calc < carbon_footprint_calc.sql
   ```

2. **Configure**
   - Update database credentials in `config.php`
   - Place files in your web server directory

3. **Run**
   - Access via `http://localhost/Carbon-Footprint-Calculator`

## 📋 Requirements

- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

## 🔧 Tech Stack

- **Frontend**: Bootstrap 5, Chart.js, jQuery
- **Backend**: PHP with prepared statements
- **Database**: MySQL with regional emission data

## 🛡️ Security Features

- SQL injection protection
- Input validation and sanitization
- Secure session management
- XSS protection

## 📊 Calculations Include

- Personal transportation (cars, bikes)
- Public transport usage
- Flight emissions
- Home energy consumption
- Baseline for other activities

## 📞 Support

Create an issue on GitHub for support.

## 📄 License

MIT License - see [LICENSE](LICENSE) file.