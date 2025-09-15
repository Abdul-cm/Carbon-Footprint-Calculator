# Carbon Footprint Calculator

A comprehensive web application for calculating and analyzing personal carbon footprints. This tool helps users understand their environmental impact and provides personalized recommendations for reducing carbon emissions.

## 🌱 Features

- **Multi-step Calculator**: Comprehensive assessment covering transportation, energy usage, and lifestyle
- **Regional Comparisons**: Compare your footprint against regional and national averages
- **Visual Analytics**: Interactive charts showing emissions breakdown
- **Personalized Recommendations**: Custom advice based on your carbon footprint
- **Secure Data Handling**: Protected against SQL injection and other security vulnerabilities
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

## 📊 What We Calculate

The calculator evaluates carbon emissions from:

1. **Personal Transportation**: Cars, motorbikes, and their fuel types
2. **Public Transportation**: Buses, trains, trams, and underground services
3. **Long-Distance Travel**: Domestic and international flights
4. **Home Energy Usage**: Electricity and gas consumption
5. **Other Activities**: Baseline estimate for food, shopping, and other lifestyle factors

## 🛠️ Technical Stack

- **Frontend**: HTML5, CSS3, JavaScript (jQuery), Bootstrap 5
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Charts**: Chart.js for data visualization
- **Security**: Prepared statements, input validation, session management

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx)
- Modern web browser with JavaScript enabled

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Abdul-cm/Carbon-Footprint-Calculator.git
   cd Carbon-Footprint-Calculator
   ```

2. **Database Setup**
   - Create a MySQL database named `carbon_footprint_calc`
   - Import the SQL structure:
     ```bash
     mysql -u your_username -p carbon_footprint_calc < carbon_footprint_calc.sql
     ```

3. **Configure Database Connection**
   - Update the database credentials in `config.php`:
     ```php
     define('DB_HOST', 'your_host');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'carbon_footprint_calc');
     ```

4. **Web Server Setup**
   - Ensure your web server is configured to serve PHP files
   - Set the document root to the project directory
   - Ensure write permissions for session handling

5. **Access the Application**
   - Navigate to your domain/localhost in a web browser
   - Start with `home.php` or set it as your index page

## 🔧 Configuration

### Environment Settings
- **Timezone**: Default set to 'Europe/London' in `config.php`
- **Session Timeout**: Configurable in `config.php` (default: 1 hour)
- **Database Charset**: UTF-8 for international character support

### Security Features
- **Prepared Statements**: All database queries use prepared statements
- **Input Validation**: Server-side validation for all form inputs
- **Session Security**: Secure session management with regeneration
- **XSS Protection**: Input sanitization and output encoding

## 🗃️ Database Schema

### Tables

#### `results`
Stores individual calculation results with all input parameters and calculated values.

#### `regional_data`
Contains average carbon footprint data for UK regions:
- North East, North West, Yorkshire & The Humber
- East Midlands, West Midlands, East
- London, South East, South West
- Wales, Scotland, Northern Ireland

## 📱 User Journey

1. **Step 1**: Location details (region and postcode)
2. **Step 2**: Personal transportation habits
3. **Step 3**: Public transportation usage
4. **Step 4**: Long-distance travel and flights
5. **Step 5**: Home energy consumption
6. **Results**: Comprehensive analysis with recommendations

## 🔍 Carbon Calculation Methodology

### Emission Factors (kg CO2e)
- **Electricity**: 0.21233 kg CO2e per kWh
- **Gas**: 0.20297 kg CO2e per kWh
- **Vehicle Types**: Varies by fuel type and efficiency
- **Public Transport**: Distance-based calculations
- **Flights**: Distance-based with altitude multipliers

### Baseline Additions
- **Other Activities**: 1.7 tonnes CO2e (food, shopping, services)

## 🎨 Customization

### Styling
- Main styles in `styles.css`
- Bootstrap 5 components with custom theme
- CSS custom properties for easy color scheme changes

### Adding New Regions
Update the `regional_data` table with new regions and their average emissions.

### Modifying Emission Factors
Update the multipliers in the calculation files to reflect current scientific data.

## 🔐 Security Considerations

- All user inputs are validated and sanitized
- Database queries use prepared statements
- Session management includes timeout and regeneration
- Error logging for debugging without exposing sensitive information
- No direct file uploads or execution of user-provided code

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Add comments for complex logic
- Test all form validations
- Ensure responsive design compatibility

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Abdul-cm**
- GitHub: [@Abdul-cm](https://github.com/Abdul-cm)
- Email: samwra34@gmail.com

## 🙏 Acknowledgments

- Carbon emission factors based on UK government data
- Bootstrap team for the UI framework
- Chart.js for visualization capabilities
- PHP community for security best practices

## 📈 Future Enhancements

- [ ] User accounts and historical tracking
- [ ] Export results to PDF
- [ ] Multi-language support
- [ ] API for third-party integrations
- [ ] Mobile app version
- [ ] Social sharing features
- [ ] Carbon offset marketplace integration

## 🐛 Known Issues

- Regional data currently focuses on UK regions
- Calculation methodology may need updates as emission factors change
- Some older browsers may not fully support all features

## 📞 Support

For support, email samwra34@gmail.com or create an issue on GitHub.

---

**Contributing to a sustainable future, one calculation at a time.** 🌍