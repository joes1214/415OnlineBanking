Currently there are two tables for the database

transaction_table
user

user contains:
username 
password
email
add_street | address street name
add_town   | address town name
add_state  | address state (2 char)
add_zip    | address zip code
add_aptnum | address apt num (not required)
acc_type   | account type, admin/user
accountnum * primary key

transaction_table contains:
acc_num * foregin key
date
withdrawl
deposit
balance
transaction_name
