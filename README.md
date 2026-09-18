# POS Monitor

POS Monitor is a web-based helper for an existing offline Java point-of-sale system.

The POS already handles the main business operations. The missing capability was remote visibility: the owner wanted to check sales, inventory, customers, suppliers, and activity from a phone or another computer.

This project connects to the existing `onpos` database and provides a browser-based monitoring interface without replacing the original POS.

## Current Features

- Sales and transaction reports
- Daily, hourly, monthly, and department sales views
- Top-selling products
- Inventory and stock monitoring
- Customer and supplier views
- Audit trail support
- Monitor user login and access control
- Windows/XAMPP installation script

## Project Status

This project is still under development. It was created to solve a real client need and continues to evolve as more requirements and security considerations are identified.

Because the system handles business, sales, inventory, and customer data, cybersecurity is an important part of the ongoing work. Authentication, permissions, password handling, database protection, secure remote access, backups, and deployment practices still need continuous review before production use.

## Installation

See [INSTALL.md](INSTALL.md) for the Windows/XAMPP installation process.

The monitor expects an existing `onpos` database. The setup script creates the monitor user table but does not replace the POS data.

## Remote Access

The system can be accessed remotely through a properly secured tunnel such as ngrok. Remote access should only be enabled after reviewing authentication, HTTPS, credentials, permissions, and firewall settings.

## Why This Project Exists

A client problem does not always require rebuilding everything. In this case, the existing offline POS was useful and already worked well. The goal was to add the missing online monitoring capability around it while keeping the original POS as the operational system and source of truth.
