<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

class Catalog {
    public function seed_category_map() {
        return [
            ['id' => 138, 'name' => 'Men\'s Clothing', 'parent' => 0],
            ['id' => 141, 'name' => 'Men\'s Underwear', 'parent' => 138],
            ['id' => 142, 'name' => 'Undershirts', 'parent' => 141],
            ['id' => 143, 'name' => 'Boxers & Briefs', 'parent' => 141],
            ['id' => 144, 'name' => 'Socks', 'parent' => 141],
            ['id' => 145, 'name' => 'Thermal Underwear', 'parent' => 141],
            ['id' => 146, 'name' => 'Sleepwear', 'parent' => 141],
            ['id' => 147, 'name' => 'Pants', 'parent' => 138],
            ['id' => 148, 'name' => 'Down Pants', 'parent' => 147],
            ['id' => 150, 'name' => 'Straight Pants', 'parent' => 147],
            ['id' => 20238, 'name' => 'Cargo Pants', 'parent' => 147],
            ['id' => 153, 'name' => 'Sweat Pants', 'parent' => 147],
            ['id' => 149, 'name' => 'Gym Shorts', 'parent' => 147],
            ['id' => 151, 'name' => 'Denim Shorts', 'parent' => 147],
            ['id' => 152, 'name' => 'Board Shorts', 'parent' => 147],
            ['id' => 154, 'name' => 'Jackets', 'parent' => 138],
            ['id' => 155, 'name' => 'Aviator Jacket', 'parent' => 154],
            ['id' => 156, 'name' => 'Skin Coats', 'parent' => 154],
            ['id' => 157, 'name' => 'Denim Jacket', 'parent' => 154],
            ['id' => 158, 'name' => 'Woolen Coat', 'parent' => 154],
            ['id' => 159, 'name' => 'Leather Coat', 'parent' => 154],
            ['id' => 165, 'name' => 'Tops', 'parent' => 138],
            ['id' => 166, 'name' => 'Stripe Polo', 'parent' => 165],
            ['id' => 167, 'name' => 'Plain T-Shirt', 'parent' => 165],
            ['id' => 168, 'name' => 'Plaid Polo', 'parent' => 165],
            ['id' => 169, 'name' => 'Long Polo', 'parent' => 165],
            ['id' => 170, 'name' => 'Shorts Polo', 'parent' => 165],
            ['id' => 171, 'name' => 'Long T-Shirt', 'parent' => 165],
            ['id' => 172, 'name' => 'Blazer & Suits', 'parent' => 138],
            ['id' => 173, 'name' => 'Single Breasted Suit', 'parent' => 172],
            ['id' => 174, 'name' => 'Blazers', 'parent' => 172],
            ['id' => 175, 'name' => 'Double Breasted Suit', 'parent' => 172],
            ['id' => 176, 'name' => 'Suit Pants', 'parent' => 172],
            ['id' => 177, 'name' => 'Suits', 'parent' => 172],
            ['id' => 178, 'name' => 'Suit Jackets', 'parent' => 172],
            ['id' => 179, 'name' => 'Shirts', 'parent' => 138],
            ['id' => 180, 'name' => 'Printed Shirt', 'parent' => 179],
            ['id' => 181, 'name' => 'Formal Shirt', 'parent' => 179],
            ['id' => 182, 'name' => 'Plain Shirt', 'parent' => 179],
            ['id' => 183, 'name' => 'Cotton Linen Shirt', 'parent' => 179],
            ['id' => 184, 'name' => 'Cargo Shirt', 'parent' => 179],
            ['id' => 185, 'name' => 'Shirt Jacket', 'parent' => 179],
            ['id' => 186, 'name' => 'Coats', 'parent' => 138],
            ['id' => 187, 'name' => 'Long Down Jacket', 'parent' => 186],
            ['id' => 188, 'name' => 'Turtleneck Parkas', 'parent' => 186],
            ['id' => 189, 'name' => 'Short Down Jacket', 'parent' => 186],
            ['id' => 190, 'name' => 'Hooded Down Jacket', 'parent' => 186],
            ['id' => 191, 'name' => 'Lightweight Down Jacket', 'parent' => 186],
            ['id' => 192, 'name' => 'Long Parkas', 'parent' => 186],
            ['id' => 193, 'name' => 'Jeans', 'parent' => 138],
            ['id' => 194, 'name' => 'Baggy Jeans', 'parent' => 193],
            ['id' => 195, 'name' => 'Tapered Jeans', 'parent' => 193],
            ['id' => 196, 'name' => 'Ripped Denim', 'parent' => 193],
            ['id' => 197, 'name' => 'Brushed Denim', 'parent' => 193],
            ['id' => 198, 'name' => 'Washed Jeans', 'parent' => 193],
            ['id' => 199, 'name' => 'Slim Jeans', 'parent' => 193],
            ['id' => 200, 'name' => 'Hoodies', 'parent' => 138],
            ['id' => 201, 'name' => 'Turtleneck Sweatshirt', 'parent' => 200],
            ['id' => 202, 'name' => 'Pullover Hoodies', 'parent' => 200],
            ['id' => 203, 'name' => 'Cardigan Sweatshirt', 'parent' => 200],
            ['id' => 204, 'name' => 'Round Neck Sweatshirt', 'parent' => 200],
            ['id' => 205, 'name' => 'Zip Up Hoodies', 'parent' => 200],
            ['id' => 160, 'name' => 'Women\'s Clothing', 'parent' => 0],
            ['id' => 211, 'name' => 'Outerwears', 'parent' => 160],
            ['id' => 212, 'name' => 'Long Down Coat', 'parent' => 211],
            ['id' => 213, 'name' => 'Short Parkas', 'parent' => 211],
            ['id' => 214, 'name' => 'Cardigans', 'parent' => 211],
            ['id' => 215, 'name' => 'Denim Coat', 'parent' => 211],
            ['id' => 216, 'name' => 'Leather & Fur', 'parent' => 211],
            ['id' => 217, 'name' => 'Dresses', 'parent' => 160],
            ['id' => 221, 'name' => 'Party Dresses', 'parent' => 217],
            ['id' => 222, 'name' => 'Long Sleeve Dresses', 'parent' => 217],
            ['id' => 223, 'name' => 'Short Dresses', 'parent' => 217],
            ['id' => 218, 'name' => 'Long Dresses', 'parent' => 217],
            ['id' => 219, 'name' => 'Midi Dresses', 'parent' => 217],
            ['id' => 220, 'name' => 'Knitted Dresses', 'parent' => 217],
            ['id' => 224, 'name' => 'Bottoms', 'parent' => 160],
            ['id' => 225, 'name' => 'Shorts', 'parent' => 224],
            ['id' => 226, 'name' => 'Women Pants', 'parent' => 224],
            ['id' => 227, 'name' => 'Skirts', 'parent' => 224],
            ['id' => 235, 'name' => 'Plus Size & Curve', 'parent' => 160],
            ['id' => 237, 'name' => 'Plus Size Matching Sets', 'parent' => 235],
            ['id' => 238, 'name' => 'Plus Size Tops', 'parent' => 235],
            ['id' => 239, 'name' => 'Plus Size Swimwears', 'parent' => 235],
            ['id' => 240, 'name' => 'Plus Size Outwears', 'parent' => 235],
            ['id' => 236, 'name' => 'Plus Size Dresses', 'parent' => 235],
            ['id' => 228, 'name' => 'Work Wear & Uniforms', 'parent' => 160],
            ['id' => 229, 'name' => 'School Uniforms', 'parent' => 228],
            ['id' => 230, 'name' => 'Military Uniforms', 'parent' => 228],
            ['id' => 231, 'name' => 'Hotel Uniforms', 'parent' => 228],
            ['id' => 232, 'name' => 'Food Service', 'parent' => 228],
            ['id' => 233, 'name' => 'Waiter Uniforms', 'parent' => 228],
            ['id' => 234, 'name' => 'Workshop Uniforms', 'parent' => 228],
            ['id' => 5827, 'name' => 'Kid\'s Clothing', 'parent' => 0],
            ['id' => 5828, 'name' => 'Hoodies', 'parent' => 5827],
            ['id' => 5829, 'name' => 'Sets', 'parent' => 5827],
            ['id' => 5830, 'name' => 'Outerwear', 'parent' => 5827],
            ['id' => 5831, 'name' => 'Loungewear', 'parent' => 5827],
            ['id' => 5832, 'name' => 'Sweater', 'parent' => 5827],
            ['id' => 5833, 'name' => 'Socks', 'parent' => 5827],
            ['id' => 5838, 'name' => 'Kid\'s Accessories', 'parent' => 5827],
            ['id' => 5839, 'name' => 'Baby & Kid Bag', 'parent' => 5838],
            ['id' => 5840, 'name' => 'Bibs & Burp Cloths', 'parent' => 5838],
            ['id' => 5841, 'name' => 'Accessories', 'parent' => 5838],
            ['id' => 5842, 'name' => 'Hats, Scarves & Gloves', 'parent' => 5838],
            ['id' => 161, 'name' => 'Computer, Office & Education', 'parent' => 0],
            ['id' => 248, 'name' => 'Computer Peripherals', 'parent' => 161],
            ['id' => 250, 'name' => 'Keyboard / Mouse / Combo', 'parent' => 248],
            ['id' => 251, 'name' => 'Mice & Keyboard Accessories', 'parent' => 248],
            ['id' => 252, 'name' => 'Webcams', 'parent' => 248],
            ['id' => 20014, 'name' => 'Webcam Accs.', 'parent' => 248],
            ['id' => 28161, 'name' => 'Video Cards', 'parent' => 248],
            ['id' => 253, 'name' => 'Graphics & Digital Tablet', 'parent' => 248],
            ['id' => 8555, 'name' => 'LCD & LED Monitors', 'parent' => 248],
            ['id' => 255, 'name' => 'Mouse', 'parent' => 248],
            ['id' => 254, 'name' => 'Mouse Pads', 'parent' => 248],
            ['id' => 19940, 'name' => 'Cables & Connectors', 'parent' => 248],
            ['id' => 245, 'name' => 'Computer Components', 'parent' => 161],
            ['id' => 256, 'name' => 'RAMs', 'parent' => 245],
            ['id' => 257, 'name' => 'Add On Cards & Controller Panels', 'parent' => 245],
            ['id' => 258, 'name' => 'CPU', 'parent' => 245],
            ['id' => 5320, 'name' => 'Used CPU', 'parent' => 245],
            ['id' => 259, 'name' => 'Barebone & Mini PC', 'parent' => 245],
            ['id' => 260, 'name' => 'Computer Cases & Towers', 'parent' => 245],
            ['id' => 261, 'name' => 'Sound Cards', 'parent' => 245],
            ['id' => 5282, 'name' => 'Desktops', 'parent' => 161],
            ['id' => 241, 'name' => 'Laptop', 'parent' => 161],
            ['id' => 266, 'name' => 'Laptop Batteries', 'parent' => 241],
            ['id' => 267, 'name' => 'Laptops', 'parent' => 241],
            ['id' => 268, 'name' => 'Laptop Motherboard', 'parent' => 241],
            ['id' => 269, 'name' => 'Replacement Keyboards', 'parent' => 241],
            ['id' => 270, 'name' => 'Laptop Repair Components', 'parent' => 241],
            ['id' => 271, 'name' => 'Laptop LCD Screen', 'parent' => 241],
            ['id' => 247, 'name' => 'Laptop Accessories', 'parent' => 161],
            ['id' => 272, 'name' => 'Laptop Docking Stations', 'parent' => 247],
            ['id' => 273, 'name' => 'Laptop Adapter', 'parent' => 247],
            ['id' => 274, 'name' => 'PC Cleaners', 'parent' => 247],
            ['id' => 275, 'name' => 'Screen Protectors', 'parent' => 247],
            ['id' => 5221, 'name' => 'Laptop Bags & Cases', 'parent' => 247],
            ['id' => 276, 'name' => 'Laptop Skins', 'parent' => 247],
            ['id' => 277, 'name' => 'Laptop Lock', 'parent' => 247],
            ['id' => 244, 'name' => 'Tablets & Accessories', 'parent' => 161],
            ['id' => 278, 'name' => 'Tablet Keyboard', 'parent' => 244],
            ['id' => 279, 'name' => 'Tablet Decals', 'parent' => 244],
            ['id' => 280, 'name' => 'Tablet Screen Protectors', 'parent' => 244],
            ['id' => 281, 'name' => 'Tablet Pen', 'parent' => 244],
            ['id' => 282, 'name' => 'Tablets', 'parent' => 244],
            ['id' => 283, 'name' => 'Tablets & E-Books Case', 'parent' => 244],
            ['id' => 246, 'name' => '3D Printing & Additive', 'parent' => 161],
            ['id' => 262, 'name' => '3D Printer Parts & Accessories', 'parent' => 246],
            ['id' => 263, 'name' => '3D Printing Materials', 'parent' => 246],
            ['id' => 264, 'name' => '3D Pens', 'parent' => 246],
            ['id' => 265, 'name' => '3D Printer', 'parent' => 246],
            ['id' => 21231, 'name' => 'Printing Equipment', 'parent' => 161],
            ['id' => 21232, 'name' => 'T-Shirt Printers', 'parent' => 21231],
            ['id' => 21234, 'name' => 'Heat Press', 'parent' => 21231],
            ['id' => 21233, 'name' => 'Printer Accs.', 'parent' => 21231],
            ['id' => 249, 'name' => 'Networking Device', 'parent' => 161],
            ['id' => 284, 'name' => 'Network Cards', 'parent' => 249],
            ['id' => 285, 'name' => 'Access Points & Accessories', 'parent' => 249],
            ['id' => 286, 'name' => 'Mobile Wi-Fi', 'parent' => 249],
            ['id' => 287, 'name' => 'WIFI Adapters & Antenna', 'parent' => 249],
            ['id' => 288, 'name' => 'Wireless WIFI Extender', 'parent' => 249],
            ['id' => 289, 'name' => 'Network Hubs', 'parent' => 249],
            ['id' => 243, 'name' => 'Office & Education', 'parent' => 161],
            ['id' => 290, 'name' => 'Gel Pens', 'parent' => 243],
            ['id' => 291, 'name' => 'Ink Supplies', 'parent' => 243],
            ['id' => 292, 'name' => 'Highlighters', 'parent' => 243],
            ['id' => 293, 'name' => 'Fountain Pens', 'parent' => 243],
            ['id' => 294, 'name' => 'Markers', 'parent' => 243],
            ['id' => 163, 'name' => 'Furniture', 'parent' => 0],
            ['id' => 295, 'name' => 'Bedroom Furniture', 'parent' => 163],
            ['id' => 305, 'name' => 'Mattresses', 'parent' => 295],
            ['id' => 306, 'name' => 'Dressers', 'parent' => 295],
            ['id' => 307, 'name' => 'Night Stands', 'parent' => 295],
            ['id' => 308, 'name' => 'Bed', 'parent' => 295],
            ['id' => 309, 'name' => 'Wardrobes', 'parent' => 295],
            ['id' => 310, 'name' => 'Chaise Lounge', 'parent' => 295],
            ['id' => 296, 'name' => 'Living Room Furniture', 'parent' => 163],
            ['id' => 311, 'name' => 'Living Room Chairs', 'parent' => 296],
            ['id' => 312, 'name' => 'Stools & Ottomans', 'parent' => 296],
            ['id' => 313, 'name' => 'Living Room Cabinets', 'parent' => 296],
            ['id' => 314, 'name' => 'Coat Racks', 'parent' => 296],
            ['id' => 315, 'name' => 'TV Stands', 'parent' => 296],
            ['id' => 316, 'name' => 'Coffee Tables', 'parent' => 296],
            ['id' => 297, 'name' => 'Outdoor Furniture', 'parent' => 163],
            ['id' => 317, 'name' => 'Hammocks', 'parent' => 297],
            ['id' => 318, 'name' => 'Garden Furniture Sets', 'parent' => 297],
            ['id' => 319, 'name' => 'Patio Furniture', 'parent' => 297],
            ['id' => 320, 'name' => 'Plant Shelves', 'parent' => 297],
            ['id' => 321, 'name' => 'Beach Chairs', 'parent' => 297],
            ['id' => 298, 'name' => 'Office Furniture', 'parent' => 163],
            ['id' => 322, 'name' => 'Reception Desks', 'parent' => 298],
            ['id' => 323, 'name' => 'Computer Desks', 'parent' => 298],
            ['id' => 324, 'name' => 'Conference Tables & Chairs', 'parent' => 298],
            ['id' => 325, 'name' => 'Bookcase & Magazine Racks', 'parent' => 298],
            ['id' => 326, 'name' => 'Office Chairs & Sofas', 'parent' => 298],
            ['id' => 327, 'name' => 'Filing Cabinets', 'parent' => 298],
            ['id' => 299, 'name' => 'Dining Room Furniture', 'parent' => 163],
            ['id' => 328, 'name' => 'Dining Room Sets', 'parent' => 299],
            ['id' => 329, 'name' => 'Bar & Wine Cabinets', 'parent' => 299],
            ['id' => 330, 'name' => 'Dining Chairs, Stools & Benches', 'parent' => 299],
            ['id' => 331, 'name' => 'Bar Tables', 'parent' => 299],
            ['id' => 332, 'name' => 'Bar Chairs & Stools', 'parent' => 299],
            ['id' => 333, 'name' => 'Dining Tables', 'parent' => 299],
            ['id' => 300, 'name' => 'Accessories & Parts', 'parent' => 163],
            ['id' => 334, 'name' => 'Furniture Parts', 'parent' => 300],
            ['id' => 335, 'name' => 'Furniture Accessories', 'parent' => 300],
            ['id' => 301, 'name' => 'Children Furniture', 'parent' => 163],
            ['id' => 336, 'name' => 'Children\'s Bookcases', 'parent' => 301],
            ['id' => 337, 'name' => 'Children Chairs & Stools', 'parent' => 301],
            ['id' => 338, 'name' => 'Children Tables & Sets', 'parent' => 301],
            ['id' => 339, 'name' => 'Children Beds', 'parent' => 301],
            ['id' => 340, 'name' => 'Children\'s Sofas', 'parent' => 301],
            ['id' => 302, 'name' => 'Commercial Furniture', 'parent' => 163],
            ['id' => 341, 'name' => 'Salon Furniture', 'parent' => 302],
            ['id' => 342, 'name' => 'Hotel Furniture', 'parent' => 302],
            ['id' => 343, 'name' => 'Restaurant Furniture', 'parent' => 302],
            ['id' => 344, 'name' => 'Theater Furniture', 'parent' => 302],
            ['id' => 303, 'name' => 'Kitchen Furniture', 'parent' => 163],
            ['id' => 345, 'name' => 'Kitchen Cabinets', 'parent' => 303],
            ['id' => 346, 'name' => 'Kitchen Islands & Trolleys', 'parent' => 303],
            ['id' => 304, 'name' => 'Bathroom Furniture', 'parent' => 163],
            ['id' => 347, 'name' => 'Step Stools & Step Ladders', 'parent' => 304],
            ['id' => 348, 'name' => 'Bathroom Chairs & Stools', 'parent' => 304],
            ['id' => 349, 'name' => 'Bathroom Cabinets', 'parent' => 304],
            ['id' => 162, 'name' => 'Electronics', 'parent' => 0],
            ['id' => 350, 'name' => 'Home Appliances', 'parent' => 162],
            ['id' => 32987, 'name' => 'Coffee and Espresso Appliances', 'parent' => 350],
            ['id' => 354, 'name' => 'Portable Washing Machine', 'parent' => 350],
            ['id' => 355, 'name' => 'Electric Window Cleaners', 'parent' => 350],
            ['id' => 356, 'name' => 'Wax Heater', 'parent' => 350],
            ['id' => 357, 'name' => 'Electric Hair Brushes', 'parent' => 350],
            ['id' => 358, 'name' => 'Personal Care Appliances', 'parent' => 350],
            ['id' => 359, 'name' => 'Garment Steamers', 'parent' => 350],
            ['id' => 351, 'name' => 'Security & Protection', 'parent' => 162],
            ['id' => 360, 'name' => 'UAV System & Robot', 'parent' => 351],
            ['id' => 361, 'name' => 'Safes', 'parent' => 351],
            ['id' => 362, 'name' => 'Video Surveillance', 'parent' => 351],
            ['id' => 363, 'name' => 'Security Inspection Device', 'parent' => 351],
            ['id' => 364, 'name' => 'Fire Protection', 'parent' => 351],
            ['id' => 365, 'name' => 'Access Control', 'parent' => 351],
            ['id' => 352, 'name' => 'Smart Electronics', 'parent' => 162],
            ['id' => 366, 'name' => 'Smart Remote Control', 'parent' => 352],
            ['id' => 367, 'name' => 'Smart Home', 'parent' => 352],
            ['id' => 368, 'name' => 'Automation Kits', 'parent' => 352],
            ['id' => 369, 'name' => 'Smart Watches', 'parent' => 352],
            ['id' => 610, 'name' => 'Portable Audio & Video', 'parent' => 162],
            ['id' => 611, 'name' => 'Earphones & Headphones', 'parent' => 610],
            ['id' => 612, 'name' => 'Speakers', 'parent' => 610],
            ['id' => 613, 'name' => 'MP3 Players', 'parent' => 610],
            ['id' => 614, 'name' => 'Microphones', 'parent' => 610],
            ['id' => 615, 'name' => 'VR/AR Devices', 'parent' => 610],
            ['id' => 661, 'name' => 'Camera & Photo', 'parent' => 162],
            ['id' => 662, 'name' => 'Digital Cameras', 'parent' => 661],
            ['id' => 663, 'name' => 'Camcorders', 'parent' => 661],
            ['id' => 664, 'name' => 'Camera Drones', 'parent' => 661],
            ['id' => 665, 'name' => 'Action Cameras', 'parent' => 661],
            ['id' => 666, 'name' => 'Photo Studio Supplies', 'parent' => 661],
            ['id' => 667, 'name' => 'Camera & Photo Accs.', 'parent' => 661],
            ['id' => 34852, 'name' => 'Games & Accs.', 'parent' => 162],
            ['id' => 34853, 'name' => 'Harddisk & Boxs', 'parent' => 34852],
            ['id' => 34854, 'name' => 'Cases', 'parent' => 34852],
            ['id' => 34855, 'name' => 'Memory Cards', 'parent' => 34852],
            ['id' => 34856, 'name' => 'Bags', 'parent' => 34852],
            ['id' => 34857, 'name' => 'Screens', 'parent' => 34852],
            ['id' => 34858, 'name' => 'Gamepads', 'parent' => 34852],
            ['id' => 34859, 'name' => 'Accessories', 'parent' => 34852],
            ['id' => 353, 'name' => 'Accessories & Parts', 'parent' => 162],
            ['id' => 370, 'name' => 'Power Station', 'parent' => 353],
            ['id' => 371, 'name' => 'Batteries', 'parent' => 353],
            ['id' => 164, 'name' => 'Toys & Games', 'parent' => 0],
            ['id' => 372, 'name' => 'Pools & Water Act.', 'parent' => 164],
            ['id' => 382, 'name' => 'Baby & Kids\' Floats', 'parent' => 372],
            ['id' => 383, 'name' => 'Swimming Pool', 'parent' => 372],
            ['id' => 384, 'name' => 'Beach Sand Toys', 'parent' => 372],
            ['id' => 385, 'name' => 'Water Balloons', 'parent' => 372],
            ['id' => 386, 'name' => 'Accessories', 'parent' => 372],
            ['id' => 387, 'name' => 'Water Guns, Blasters & Soakers', 'parent' => 372],
            ['id' => 373, 'name' => 'Sports & Outdoor Act.', 'parent' => 164],
            ['id' => 388, 'name' => 'Ride On Toys & Acc.', 'parent' => 373],
            ['id' => 389, 'name' => 'Fishing Toys', 'parent' => 373],
            ['id' => 390, 'name' => 'Kites & Acc.', 'parent' => 373],
            ['id' => 391, 'name' => 'Inflatable Toys', 'parent' => 373],
            ['id' => 392, 'name' => 'Toy Sports', 'parent' => 373],
            ['id' => 393, 'name' => 'Bubbles', 'parent' => 373],
            ['id' => 374, 'name' => 'RC Toys', 'parent' => 164],
            ['id' => 394, 'name' => 'Parts & Acc.', 'parent' => 374],
            ['id' => 395, 'name' => 'RC Airplanes', 'parent' => 374],
            ['id' => 396, 'name' => 'RC Helicopters', 'parent' => 374],
            ['id' => 397, 'name' => 'RC Animals', 'parent' => 374],
            ['id' => 398, 'name' => 'RC Quadcopter', 'parent' => 374],
            ['id' => 399, 'name' => 'RC Cars', 'parent' => 374],
            ['id' => 375, 'name' => 'Bldg. and Constr. Toys', 'parent' => 164],
            ['id' => 400, 'name' => 'Marble Runs', 'parent' => 375],
            ['id' => 401, 'name' => 'Wooden Blocks', 'parent' => 375],
            ['id' => 402, 'name' => 'Soft Plastic Blocks', 'parent' => 375],
            ['id' => 403, 'name' => 'Stacking Blocks', 'parent' => 375],
            ['id' => 404, 'name' => 'Blocks', 'parent' => 375],
            ['id' => 405, 'name' => 'Electronic Blocks', 'parent' => 375],
            ['id' => 376, 'name' => 'Learning & Edu.', 'parent' => 164],
            ['id' => 406, 'name' => 'Science & Tech.', 'parent' => 376],
            ['id' => 407, 'name' => 'Toy Musical Inst.', 'parent' => 376],
            ['id' => 408, 'name' => 'Modeling Clay', 'parent' => 376],
            ['id' => 409, 'name' => 'Drawing Toys', 'parent' => 376],
            ['id' => 410, 'name' => 'Readings', 'parent' => 376],
            ['id' => 411, 'name' => 'Montessori', 'parent' => 376],
            ['id' => 377, 'name' => 'Hobby & Collect.', 'parent' => 164],
            ['id' => 412, 'name' => 'Spinning Tops', 'parent' => 377],
            ['id' => 413, 'name' => 'Yoyo', 'parent' => 377],
            ['id' => 414, 'name' => 'Party Games', 'parent' => 377],
            ['id' => 415, 'name' => 'Game Coll. Cards', 'parent' => 377],
            ['id' => 378, 'name' => 'Electronic Toys', 'parent' => 164],
            ['id' => 417, 'name' => 'Toy Cameras', 'parent' => 378],
            ['id' => 418, 'name' => 'Programmable Toys', 'parent' => 378],
            ['id' => 416, 'name' => 'Electronic Pets', 'parent' => 378],
            ['id' => 379, 'name' => 'Dolls & Accs.', 'parent' => 164],
            ['id' => 419, 'name' => 'Reborn Dolls', 'parent' => 379],
            ['id' => 420, 'name' => 'Dolls', 'parent' => 379],
            ['id' => 421, 'name' => 'Doll Houses', 'parent' => 379],
            ['id' => 422, 'name' => 'BJD Dolls', 'parent' => 379],
            ['id' => 423, 'name' => 'Dolls Accessories', 'parent' => 379],
            ['id' => 380, 'name' => 'Action & Figures', 'parent' => 164],
            ['id' => 424, 'name' => 'Fantasy Figurines', 'parent' => 380],
            ['id' => 425, 'name' => 'Transformer Robot', 'parent' => 380],
            ['id' => 426, 'name' => 'Animation Derivatives', 'parent' => 380],
            ['id' => 427, 'name' => 'Military Action Figures', 'parent' => 380],
            ['id' => 428, 'name' => 'Blind Box', 'parent' => 380],
            ['id' => 429, 'name' => 'Animal Figures', 'parent' => 380],
            ['id' => 381, 'name' => 'Plush & Stuffed', 'parent' => 164],
            ['id' => 430, 'name' => 'Plush Pillows', 'parent' => 381],
            ['id' => 431, 'name' => 'Plush Backpacks', 'parent' => 381],
            ['id' => 432, 'name' => 'Movies & TV', 'parent' => 381],
            ['id' => 433, 'name' => 'Plush Keychains', 'parent' => 381],
            ['id' => 434, 'name' => 'Puppets', 'parent' => 381],
            ['id' => 435, 'name' => 'Stuffed & Plush Animals', 'parent' => 381],
            ['id' => 566, 'name' => 'Phones and Telecommunications', 'parent' => 0],
            ['id' => 567, 'name' => 'Mobile Phones', 'parent' => 566],
            ['id' => 572, 'name' => 'Rugged Smartphones', 'parent' => 567],
            ['id' => 573, 'name' => 'Smartphone 5g', 'parent' => 567],
            ['id' => 574, 'name' => 'Smartphone Android', 'parent' => 567],
            ['id' => 568, 'name' => 'Phones Accessories', 'parent' => 566],
            ['id' => 575, 'name' => 'Phone Case', 'parent' => 568],
            ['id' => 576, 'name' => 'Screen Protectors', 'parent' => 568],
            ['id' => 577, 'name' => 'Phone Chargers', 'parent' => 568],
            ['id' => 588, 'name' => 'Wireless Chargers', 'parent' => 577],
            ['id' => 589, 'name' => 'Mobile Phone Chargers', 'parent' => 577],
            ['id' => 578, 'name' => 'Power Bank', 'parent' => 568],
            ['id' => 20015, 'name' => 'Phone Ext.', 'parent' => 568],
            ['id' => 569, 'name' => 'Handheld Transceiver', 'parent' => 566],
            ['id' => 579, 'name' => 'Walkie Talkie Accessories', 'parent' => 569],
            ['id' => 580, 'name' => 'Walkie Talkie', 'parent' => 569],
            ['id' => 570, 'name' => 'Phone Parts', 'parent' => 566],
            ['id' => 581, 'name' => 'Mobile Phone Batteries', 'parent' => 570],
            ['id' => 582, 'name' => 'Mobile Phone Antenna', 'parent' => 570],
            ['id' => 583, 'name' => 'Mobile Phone Housings', 'parent' => 570],
            ['id' => 584, 'name' => 'Mobile Phone Flex Cables', 'parent' => 570],
            ['id' => 585, 'name' => 'Mobile Phone LCD Screens', 'parent' => 570],
            ['id' => 571, 'name' => 'Communications', 'parent' => 566],
            ['id' => 586, 'name' => 'Communications Antennas', 'parent' => 571],
            ['id' => 587, 'name' => 'Fiber Optic Equipment', 'parent' => 571],
            ['id' => 206, 'name' => 'Cosplay & Costumes', 'parent' => 0],
            ['id' => 207, 'name' => 'Game Costumes', 'parent' => 206],
            ['id' => 208, 'name' => 'Movie & TV Costumes', 'parent' => 206],
            ['id' => 209, 'name' => 'Anime Costumes', 'parent' => 206],
            ['id' => 210, 'name' => 'Holidays Costumes', 'parent' => 206],
            ['id' => 15896, 'name' => 'Kids Costumes', 'parent' => 206],
            ['id' => 4332, 'name' => 'Costume Props', 'parent' => 206],
            ['id' => 755, 'name' => 'Luggage, Bags & Shoes', 'parent' => 0],
            ['id' => 759, 'name' => 'Men\'s Causal Shoes', 'parent' => 755],
            ['id' => 776, 'name' => 'Loafers', 'parent' => 759],
            ['id' => 777, 'name' => 'Casual Sneaker', 'parent' => 759],
            ['id' => 778, 'name' => 'Leather Casual', 'parent' => 759],
            ['id' => 779, 'name' => 'Skateboard', 'parent' => 759],
            ['id' => 780, 'name' => 'Canvas', 'parent' => 759],
            ['id' => 2253, 'name' => 'Men\'s Boots', 'parent' => 759],
            ['id' => 758, 'name' => 'Women\'s Casual Shoes', 'parent' => 755],
            ['id' => 783, 'name' => 'Canvas', 'parent' => 758],
            ['id' => 784, 'name' => 'Skateboard', 'parent' => 758],
            ['id' => 785, 'name' => 'Boot Shoes', 'parent' => 758],
            ['id' => 786, 'name' => 'Sneaker', 'parent' => 758],
            ['id' => 765, 'name' => 'Women\'s Boots', 'parent' => 755],
            ['id' => 787, 'name' => 'Chelsea Boots', 'parent' => 765],
            ['id' => 788, 'name' => 'Ankle Boots', 'parent' => 765],
            ['id' => 789, 'name' => 'Martin Boots', 'parent' => 765],
            ['id' => 790, 'name' => 'High Boots', 'parent' => 765],
            ['id' => 791, 'name' => 'Snow Boots', 'parent' => 765],
            ['id' => 5834, 'name' => 'Children\'s Shoes', 'parent' => 755],
            ['id' => 5835, 'name' => 'Boots', 'parent' => 5834],
            ['id' => 5836, 'name' => 'Flats', 'parent' => 5834],
            ['id' => 5837, 'name' => 'Shoes', 'parent' => 5834],
            ['id' => 764, 'name' => 'Shoe Accessories', 'parent' => 755],
            ['id' => 792, 'name' => 'Shoe Trees', 'parent' => 764],
            ['id' => 793, 'name' => 'Shoelaces', 'parent' => 764],
            ['id' => 794, 'name' => 'Shoe Covers', 'parent' => 764],
            ['id' => 795, 'name' => 'Shoe Decorations', 'parent' => 764],
            ['id' => 796, 'name' => 'Shoe Polish', 'parent' => 764],
            ['id' => 797, 'name' => 'Shoe Care Kit', 'parent' => 764],
            ['id' => 761, 'name' => 'Flats', 'parent' => 755],
            ['id' => 798, 'name' => 'Loafers', 'parent' => 761],
            ['id' => 799, 'name' => 'Oxfords', 'parent' => 761],
            ['id' => 800, 'name' => 'Chinese Style', 'parent' => 761],
            ['id' => 801, 'name' => 'Leather Shoes', 'parent' => 761],
            ['id' => 802, 'name' => 'Hidden Heel', 'parent' => 761],
            ['id' => 760, 'name' => 'Wallet & ID Holder', 'parent' => 755],
            ['id' => 804, 'name' => 'ID Holder', 'parent' => 760],
            ['id' => 805, 'name' => 'Men\'s Fold Wallet', 'parent' => 760],
            ['id' => 806, 'name' => 'Women\'s Wallet with Pattern', 'parent' => 760],
            ['id' => 807, 'name' => 'Men\'s Leather Wallet', 'parent' => 760],
            ['id' => 808, 'name' => 'Women\'s Leather Wallet', 'parent' => 760],
            ['id' => 757, 'name' => 'Handbags', 'parent' => 755],
            ['id' => 809, 'name' => 'Messenger Bag', 'parent' => 757],
            ['id' => 810, 'name' => 'Shell Bag', 'parent' => 757],
            ['id' => 811, 'name' => 'Saddle Bag', 'parent' => 757],
            ['id' => 812, 'name' => 'Satchels Bag', 'parent' => 757],
            ['id' => 813, 'name' => 'Pillow Bag', 'parent' => 757],
            ['id' => 814, 'name' => 'Baguette Bag', 'parent' => 757],
            ['id' => 762, 'name' => 'Backpack', 'parent' => 755],
            ['id' => 815, 'name' => 'Tactical Backpack', 'parent' => 762],
            ['id' => 816, 'name' => 'High-Capacity Backpack', 'parent' => 762],
            ['id' => 817, 'name' => 'USB Charging Backpack', 'parent' => 762],
            ['id' => 818, 'name' => 'Anti-Theft Backpack', 'parent' => 762],
            ['id' => 819, 'name' => 'Business Backpack', 'parent' => 762],
            ['id' => 820, 'name' => 'Order Backpack', 'parent' => 762],
            ['id' => 763, 'name' => 'Other Bags & Accs.', 'parent' => 755],
            ['id' => 821, 'name' => 'Other Kid\'s Bags', 'parent' => 763],
            ['id' => 822, 'name' => 'Bag Accs.', 'parent' => 763],
            ['id' => 823, 'name' => 'Luggage Cover', 'parent' => 763],
            ['id' => 824, 'name' => 'Luggage Tags', 'parent' => 763],
            ['id' => 756, 'name' => 'Jewelry, Watches & Accs.', 'parent' => 0],
            ['id' => 766, 'name' => 'Jewelry Making', 'parent' => 756],
            ['id' => 825, 'name' => 'Charms', 'parent' => 766],
            ['id' => 826, 'name' => 'Jewelry Packaging', 'parent' => 766],
            ['id' => 827, 'name' => 'Chain', 'parent' => 766],
            ['id' => 828, 'name' => 'Tools', 'parent' => 766],
            ['id' => 767, 'name' => 'Scarves & Gloves', 'parent' => 756],
            ['id' => 829, 'name' => 'Children\'s Gloves', 'parent' => 767],
            ['id' => 830, 'name' => 'Cashmere Wool Scarf', 'parent' => 767],
            ['id' => 831, 'name' => 'Plain Scarf', 'parent' => 767],
            ['id' => 832, 'name' => 'Silk Scarf', 'parent' => 767],
            ['id' => 833, 'name' => 'Leather Gloves', 'parent' => 767],
            ['id' => 834, 'name' => 'Tactical Gloves', 'parent' => 767],
            ['id' => 768, 'name' => 'Other Apparel Accs.', 'parent' => 756],
            ['id' => 835, 'name' => 'Apparel Fabrics & Textiles', 'parent' => 768],
            ['id' => 836, 'name' => 'Fashionable Canes', 'parent' => 768],
            ['id' => 837, 'name' => 'Ties', 'parent' => 768],
            ['id' => 838, 'name' => 'Handkerchiefs', 'parent' => 768],
            ['id' => 769, 'name' => 'Other Jewelry', 'parent' => 756],
            ['id' => 839, 'name' => 'Hair Jewelry', 'parent' => 769],
            ['id' => 840, 'name' => 'Key Chains', 'parent' => 769],
            ['id' => 841, 'name' => 'Jewelry Sets', 'parent' => 769],
            ['id' => 842, 'name' => 'Brooches', 'parent' => 769],
            ['id' => 843, 'name' => 'Tie Clips & Cufflinks', 'parent' => 769],
            ['id' => 770, 'name' => 'Headwear', 'parent' => 756],
            ['id' => 844, 'name' => 'Hair Elastics & Ties', 'parent' => 770],
            ['id' => 845, 'name' => 'Kid\'s Hair Accs.', 'parent' => 770],
            ['id' => 846, 'name' => 'Bandanas And Nightcaps', 'parent' => 770],
            ['id' => 847, 'name' => 'Headband', 'parent' => 770],
            ['id' => 771, 'name' => 'Hat', 'parent' => 756],
            ['id' => 848, 'name' => 'Fleece Lined Hats', 'parent' => 771],
            ['id' => 849, 'name' => 'Sun Hats', 'parent' => 771],
            ['id' => 850, 'name' => 'Fun Hats', 'parent' => 771],
            ['id' => 851, 'name' => 'Baseball Caps', 'parent' => 771],
            ['id' => 852, 'name' => 'One Piece Hats', 'parent' => 771],
            ['id' => 853, 'name' => 'Straw Bucket Hats', 'parent' => 771],
            ['id' => 772, 'name' => 'Belt', 'parent' => 756],
            ['id' => 854, 'name' => 'Women\'s Belts', 'parent' => 772],
            ['id' => 855, 'name' => 'Fashion Belts', 'parent' => 772],
            ['id' => 856, 'name' => 'Leather Belts', 'parent' => 772],
            ['id' => 857, 'name' => 'Outdoor Belts', 'parent' => 772],
            ['id' => 858, 'name' => 'Cummerbunds', 'parent' => 772],
            ['id' => 859, 'name' => 'Waist Chain', 'parent' => 772],
            ['id' => 773, 'name' => 'Bracelet & Bangles', 'parent' => 756],
            ['id' => 860, 'name' => 'Women\'s Bracelet', 'parent' => 773],
            ['id' => 861, 'name' => 'Thread Bracelet', 'parent' => 773],
            ['id' => 862, 'name' => 'Stainless Steel Bracelet', 'parent' => 773],
            ['id' => 863, 'name' => 'Men\'s Bracelet', 'parent' => 773],
            ['id' => 864, 'name' => 'Nature Stone Bracelet', 'parent' => 773],
            ['id' => 865, 'name' => 'Pendant Bracelet', 'parent' => 773],
            ['id' => 774, 'name' => 'Earrings', 'parent' => 756],
            ['id' => 866, 'name' => 'Stud', 'parent' => 774],
            ['id' => 867, 'name' => 'Moissanite Earring', 'parent' => 774],
            ['id' => 868, 'name' => 'Drop & Dangle Earring', 'parent' => 774],
            ['id' => 869, 'name' => 'Tassle Earring', 'parent' => 774],
            ['id' => 870, 'name' => 'Pearl Earrings', 'parent' => 774],
            ['id' => 871, 'name' => 'Hoop Earring', 'parent' => 774],
            ['id' => 775, 'name' => 'Sunglasses', 'parent' => 756],
            ['id' => 872, 'name' => 'Outdoor Sunglasses', 'parent' => 775],
            ['id' => 873, 'name' => 'Polarized Sunglasses', 'parent' => 775],
            ['id' => 874, 'name' => 'Children\'s Glasses', 'parent' => 775],
            ['id' => 875, 'name' => 'Women\'s Sunglasses', 'parent' => 775],
            ['id' => 876, 'name' => 'Men\'s Sunglasses', 'parent' => 775],
            ['id' => 2151, 'name' => 'Men\'s Watches', 'parent' => 756],
            ['id' => 2152, 'name' => 'Digital Watches', 'parent' => 2151],
            ['id' => 2153, 'name' => 'Watch Cases', 'parent' => 2151],
            ['id' => 2154, 'name' => 'Watchbands', 'parent' => 2151],
            ['id' => 2155, 'name' => 'Mechanical Watches', 'parent' => 2151],
            ['id' => 2156, 'name' => 'Quartz Watches', 'parent' => 2151],
            ['id' => 9733, 'name' => 'Women\'s Watches', 'parent' => 756],
            ['id' => 9736, 'name' => 'Bracelet Watches', 'parent' => 9733],
            ['id' => 9737, 'name' => 'Elegant Watches', 'parent' => 9733],
            ['id' => 9745, 'name' => 'Romantic Watches', 'parent' => 9733],
            ['id' => 9746, 'name' => 'Sports Watches', 'parent' => 9733],
            ['id' => 9747, 'name' => 'Innovative Watches', 'parent' => 9733],
            ['id' => 20430, 'name' => 'Automotive', 'parent' => 0],
            ['id' => 20431, 'name' => 'Interior Accessories', 'parent' => 20430],
            ['id' => 20432, 'name' => 'Floor Mats', 'parent' => 20431],
            ['id' => 20433, 'name' => 'Car Seat Covers', 'parent' => 20431],
            ['id' => 20434, 'name' => 'Auto Fastener & Clip', 'parent' => 20431],
            ['id' => 20435, 'name' => 'Stowing Tidying', 'parent' => 20431],
            ['id' => 20436, 'name' => 'Ornaments', 'parent' => 20431],
            ['id' => 20437, 'name' => 'Accessories', 'parent' => 20431],
            ['id' => 20438, 'name' => 'Exterior Accessories', 'parent' => 20430],
            ['id' => 20439, 'name' => 'Accessories', 'parent' => 20438],
            ['id' => 20440, 'name' => 'Sunshades', 'parent' => 20438],
            ['id' => 20441, 'name' => 'Car Covers', 'parent' => 20438],
            ['id' => 20442, 'name' => 'Car Body Film', 'parent' => 20438],
            ['id' => 20443, 'name' => 'Motorcycle Gear', 'parent' => 20430],
            ['id' => 20444, 'name' => 'Snowmobile Gear', 'parent' => 20443],
            ['id' => 20445, 'name' => 'Motorcycle Pants', 'parent' => 20443],
            ['id' => 20446, 'name' => 'Gloves', 'parent' => 20443],
            ['id' => 20447, 'name' => 'Boots', 'parent' => 20443],
            ['id' => 20448, 'name' => 'Protection Gears', 'parent' => 20443],
            ['id' => 20449, 'name' => 'Helmets', 'parent' => 20443],
            ['id' => 20451, 'name' => 'Motorcycle Parts', 'parent' => 20430],
            ['id' => 20485, 'name' => 'Pedals & Pegs', 'parent' => 20451],
            ['id' => 20486, 'name' => 'Body & Frame', 'parent' => 20451],
            ['id' => 20487, 'name' => 'Handlebars', 'parent' => 20451],
            ['id' => 20488, 'name' => 'Lighting', 'parent' => 20451],
            ['id' => 20489, 'name' => 'Tires', 'parent' => 20451],
            ['id' => 20490, 'name' => 'Instruments & Gauges', 'parent' => 20451],
            ['id' => 20452, 'name' => 'Car Electronics', 'parent' => 20430],
            ['id' => 20492, 'name' => 'Car Electronic Accessories', 'parent' => 20452],
            ['id' => 20493, 'name' => 'GPS & Accessories', 'parent' => 20452],
            ['id' => 20494, 'name' => 'Car Electrical Appliances', 'parent' => 20452],
            ['id' => 20495, 'name' => 'Car Intelligent Systems', 'parent' => 20452],
            ['id' => 20496, 'name' => 'Car DVR', 'parent' => 20452],
            ['id' => 20453, 'name' => 'Motorcycles', 'parent' => 20430],
            ['id' => 20497, 'name' => 'ATV & UTV Accessories', 'parent' => 20453],
            ['id' => 20454, 'name' => 'Wear Parts', 'parent' => 20430],
            ['id' => 20499, 'name' => 'Automobile Filters', 'parent' => 20454],
            ['id' => 20500, 'name' => 'Wheel Parts', 'parent' => 20454],
            ['id' => 20501, 'name' => 'Engine Oil', 'parent' => 20454],
            ['id' => 20502, 'name' => 'Windshield Wipers', 'parent' => 20454],
            ['id' => 20503, 'name' => 'Ignition Systems', 'parent' => 20454],
            ['id' => 20504, 'name' => 'Shock Absorbers', 'parent' => 20454],
            ['id' => 20455, 'name' => 'Engines & Engine Parts', 'parent' => 20430],
            ['id' => 20505, 'name' => 'Turbos Nitrous Superchargers', 'parent' => 20455],
            ['id' => 20506, 'name' => 'Fuel Supply Systems', 'parent' => 20455],
            ['id' => 20507, 'name' => 'Exhaust Systems', 'parent' => 20455],
            ['id' => 20508, 'name' => 'Engines & Components', 'parent' => 20455],
            ['id' => 20509, 'name' => 'Air Intake Systems', 'parent' => 20455],
            ['id' => 20510, 'name' => 'Car Sensors', 'parent' => 20455],
            ['id' => 20456, 'name' => 'Ornamental Cleaning & Protection', 'parent' => 20430],
            ['id' => 20511, 'name' => 'Care Cleaning & Protection', 'parent' => 20456],
            ['id' => 20512, 'name' => 'Covers & Ornamental Mouldings', 'parent' => 20456],
            ['id' => 20513, 'name' => 'Stickers', 'parent' => 20456],
            ['id' => 20457, 'name' => 'Car Repair Tool', 'parent' => 20430],
            ['id' => 20514, 'name' => 'Car Jacks & Lifting Equipment', 'parent' => 20457],
            ['id' => 20515, 'name' => 'Car Body Repair Tool', 'parent' => 20457],
            ['id' => 20516, 'name' => 'Inspection Tools', 'parent' => 20457],
            ['id' => 20517, 'name' => 'Diagnostic Tools', 'parent' => 20457],
            ['id' => 20518, 'name' => 'Car Disassembly Tool', 'parent' => 20457],
            ['id' => 20519, 'name' => 'Tire Repair Tools', 'parent' => 20457],
            ['id' => 37046, 'name' => 'Pet Supplies', 'parent' => 0],
            ['id' => 20459, 'name' => 'Small Animals', 'parent' => 37046],
            ['id' => 20532, 'name' => 'Apparel', 'parent' => 20459],
            ['id' => 20465, 'name' => 'Exercise Wheels', 'parent' => 20459],
            ['id' => 20466, 'name' => 'Toys', 'parent' => 20459],
            ['id' => 20467, 'name' => 'Houses & Habitats', 'parent' => 20459],
            ['id' => 20468, 'name' => 'Carriers & Strollers', 'parent' => 20459],
            ['id' => 20469, 'name' => 'Collars, Harnesses & Leashes', 'parent' => 20459],
            ['id' => 20470, 'name' => 'Feeding & Watering Supplies', 'parent' => 20459],
            ['id' => 20461, 'name' => 'Cats', 'parent' => 37046],
            ['id' => 20527, 'name' => 'Repellents & Training Aids', 'parent' => 20461],
            ['id' => 20528, 'name' => 'Cat Doors, Steps, Nets & Pens', 'parent' => 20461],
            ['id' => 20529, 'name' => 'Beds & Furniture', 'parent' => 20461],
            ['id' => 20530, 'name' => 'Collars, Harnesses & Leashes', 'parent' => 20461],
            ['id' => 20531, 'name' => 'Flea & Tick Control', 'parent' => 20461],
            ['id' => 20462, 'name' => 'Dogs', 'parent' => 37046],
            ['id' => 20533, 'name' => 'Litter & Housebreaking', 'parent' => 20462],
            ['id' => 20534, 'name' => 'Memorials & Funerary', 'parent' => 20462],
            ['id' => 20535, 'name' => 'Doors, Gates & Ramps', 'parent' => 20462],
            ['id' => 20460, 'name' => 'Fish & Aquatic Pets', 'parent' => 37046],
            ['id' => 20520, 'name' => 'Aquarium Decor', 'parent' => 20460],
            ['id' => 20521, 'name' => 'Aquariums & Fish Bowls', 'parent' => 20460],
            ['id' => 20522, 'name' => 'Aquarium Accessories & Equipment', 'parent' => 20460],
            ['id' => 20523, 'name' => 'Automatic Feeders', 'parent' => 20460],
            ['id' => 20524, 'name' => 'Aquarium Pumps & Filters', 'parent' => 20460],
            ['id' => 20525, 'name' => 'Aquarium Cleaners', 'parent' => 20460],
            ['id' => 20463, 'name' => 'Reptiles & Amphibians', 'parent' => 37046],
            ['id' => 20536, 'name' => 'Habitat Decor', 'parent' => 20463],
            ['id' => 20537, 'name' => 'Terrarium Bedding, Sand & Substrate', 'parent' => 20463],
            ['id' => 20538, 'name' => 'Terrarium Heat Lamps & Mats', 'parent' => 20463],
            ['id' => 20539, 'name' => 'Accessories & Others', 'parent' => 20463],
            ['id' => 20540, 'name' => 'Terrarium Bowls', 'parent' => 20463],
            ['id' => 20541, 'name' => 'Habitat Lighting', 'parent' => 20463],
            ['id' => 20464, 'name' => 'Farm Animals', 'parent' => 37046],
            ['id' => 20542, 'name' => 'Cow Mastitis Detectors', 'parent' => 20464],
            ['id' => 20543, 'name' => 'Cages & Accessories', 'parent' => 20464],
            ['id' => 20544, 'name' => 'Ear Tags', 'parent' => 20464],
            ['id' => 24266, 'name' => 'Gift Cards', 'parent' => 0],
            ['id' => 24267, 'name' => 'Anniversary', 'parent' => 24266],
            ['id' => 24268, 'name' => 'Birthday', 'parent' => 24266],
            ['id' => 24269, 'name' => 'Christmas', 'parent' => 24266],
            ['id' => 24270, 'name' => 'Congratulations', 'parent' => 24266],
            ['id' => 24271, 'name' => 'Fourth of July', 'parent' => 24266],
            ['id' => 24272, 'name' => 'Get Well', 'parent' => 24266],
            ['id' => 24273, 'name' => 'Graduation', 'parent' => 24266],
            ['id' => 24274, 'name' => 'Halloween', 'parent' => 24266],
            ['id' => 24275, 'name' => 'New Year’s', 'parent' => 24266],
            ['id' => 24276, 'name' => 'Thank You & Appreciation', 'parent' => 24266],
            ['id' => 24277, 'name' => 'Valentine\'s Day', 'parent' => 24266],
            ['id' => 24278, 'name' => 'Default - Simple', 'parent' => 24266],
            ['id' => 24279, 'name' => 'Animated', 'parent' => 24266],
        ];
    }

    public function all_categories() {
        $excluded_ids = [18, 5017];
        $excluded_names = ['uncategorized', 'funding subscriptions', 'funding and subscriptions'];
        $seed = $this->seed_category_map();
        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);
        $live_by_id = [];
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $live_by_id[(int) $term->term_id] = $term;
            }
        }

        $raw = [];
        foreach ((array) $seed as $item) {
            $term_id = (int) $item['id'];
            if (in_array($term_id, $excluded_ids, true)) continue;
            $live = isset($live_by_id[$term_id]) ? $live_by_id[$term_id] : null;
            $name = $live ? $live->name : $item['name'];
            $slug = $live ? $live->slug : sanitize_title($name);
            $name_n = $this->normalize_text($name);
            $slug_n = $this->normalize_text(str_replace('-', ' ', $slug));
            if (in_array($name_n, $excluded_names, true) || in_array($slug_n, $excluded_names, true)) continue;
            $raw[$term_id] = [
                'id' => $term_id,
                'name' => $name,
                'slug' => $slug,
                'parent' => (int) $item['parent'],
            ];
        }

        foreach ($live_by_id as $term_id => $term) {
            if (isset($raw[$term_id]) || in_array((int) $term_id, $excluded_ids, true)) continue;
            $name_n = $this->normalize_text($term->name);
            $slug_n = $this->normalize_text(str_replace('-', ' ', $term->slug));
            if (in_array($name_n, $excluded_names, true) || in_array($slug_n, $excluded_names, true)) continue;
            $raw[$term_id] = [
                'id' => (int) $term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'parent' => (int) $term->parent,
            ];
        }

        $out = [];
        foreach ($raw as $term_id => $item) {
            $path_names = [];
            $visited = [];
            $current_id = $term_id;
            while ($current_id && isset($raw[$current_id]) && !isset($visited[$current_id])) {
                $visited[$current_id] = true;
                array_unshift($path_names, $raw[$current_id]['name']);
                $current_id = (int) $raw[$current_id]['parent'];
            }
            $item['path_names'] = $path_names;
            $item['path_string'] = implode(' ', $path_names);
            $out[] = $item;
        }

        return $out;
    }

    private function normalize_text($text) {
        $text = strtolower(wp_strip_all_tags((string) $text));
        $text = preg_replace('/[^a-z0-9\s-]+/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    public function extract_tokens($query) {
        $stop = ['a','an','and','are','best','buy','can','cheap','cheaper','compare','do','for','find','from','get','give','i','in','is','it','items','lowest','me','need','of','on','or','product','products','recommend','search','show','similar','some','stock','that','the','these','to','under','want','with'];
        $text = $this->normalize_text($query);
        if ($text === '') return [];
        $parts = preg_split('/\s+/', $text);
        $parts = array_values(array_filter(array_map('trim', $parts), function($p) use ($stop) {
            return $p !== '' && !in_array($p, $stop, true) && strlen($p) > 1;
        }));
        return array_values(array_unique($parts));
    }

    private function synonym_key_matches_category($label, $cat) {
        $key = trim((string) $label);
        if ($key === '') return false;
        if (ctype_digit($key)) return (int) $key === (int) ($cat['id'] ?? 0);

        $key_n = $this->normalize_text($key);
        $name = $this->normalize_text($cat['name'] ?? '');
        $slug = $this->normalize_text(str_replace('-', ' ', $cat['slug'] ?? ''));
        $path = $this->normalize_text($cat['path_string'] ?? '');
        return $key_n !== '' && ($key_n === $name || $key_n === $slug || $key_n === $path);
    }

    public function find_matching_categories($query, $synonyms = []) {
        $query_l = $this->normalize_text($query);
        $tokens = $this->extract_tokens($query);
        $matches = [];
        foreach ($this->all_categories() as $cat) {
            $name = $this->normalize_text($cat['name']);
            $slug = $this->normalize_text(str_replace('-', ' ', $cat['slug']));
            $path = $this->normalize_text(isset($cat['path_string']) ? $cat['path_string'] : '');
            $depth = !empty($cat['path_names']) && is_array($cat['path_names']) ? count($cat['path_names']) : 1;
            $category_tokens = array_unique(array_filter(array_merge(
                preg_split('/\s+/', $name),
                preg_split('/\s+/', $slug),
                preg_split('/\s+/', $path)
            )));
            $score = 0;

            if ($path !== '' && $query_l === $path) $score += 260;
            if ($name !== '' && $query_l === $name) $score += 220;
            if ($slug !== '' && $query_l === $slug) $score += 210;
            if ($path !== '' && preg_match('/\b' . preg_quote($path, '/') . '\b/', $query_l)) $score += 180;
            if ($name !== '' && preg_match('/\b' . preg_quote($name, '/') . '\b/', $query_l)) $score += 140;
            if ($slug !== '' && preg_match('/\b' . preg_quote($slug, '/') . '\b/', $query_l)) $score += 125;

            foreach ($tokens as $token) {
                if (in_array($token, $category_tokens, true)) $score += 18;
            }

            foreach ($synonyms as $label => $list) {
                if ($this->synonym_key_matches_category($label, $cat)) {
                    foreach ((array) $list as $alt) {
                        $alt_n = $this->normalize_text($alt);
                        if ($alt_n !== '' && $query_l === $alt_n) $score += 120;
                        if ($alt_n !== '' && preg_match('/\b' . preg_quote($alt_n, '/') . '\b/', $query_l)) $score += 85;
                    }
                }
            }

            if ($score > 0) {
                $cat['score'] = $score + ($depth * 6);
                $matches[$cat['id']] = $cat;
            }
        }
        uasort($matches, function($a, $b){ return ($b['score'] ?? 0) <=> ($a['score'] ?? 0); });
        return array_values($matches);
    }

    public function find_best_category_match($query, $synonyms = []) {
        $query_l = $this->normalize_text($query);
        if ($query_l === '') return null;

        $best = null;
        $best_score = 0;
        foreach ($this->all_categories() as $cat) {
            $name = $this->normalize_text($cat['name']);
            $slug = $this->normalize_text(str_replace('-', ' ', $cat['slug']));
            $path = $this->normalize_text(isset($cat['path_string']) ? $cat['path_string'] : '');
            $depth = !empty($cat['path_names']) && is_array($cat['path_names']) ? count($cat['path_names']) : 1;
            $score = 0;

            if ($path !== '' && $query_l === $path) $score += 320;
            if ($name !== '' && $query_l === $name) $score += 280;
            if ($slug !== '' && $query_l === $slug) $score += 270;
            if ($path !== '' && preg_match('/\b' . preg_quote($path, '/') . '\b/', $query_l)) $score += 220;
            if ($name !== '' && preg_match('/\b' . preg_quote($name, '/') . '\b/', $query_l)) $score += 180;
            if ($slug !== '' && preg_match('/\b' . preg_quote($slug, '/') . '\b/', $query_l)) $score += 160;

            foreach ($synonyms as $label => $list) {
                if ($this->synonym_key_matches_category($label, $cat)) {
                    foreach ((array) $list as $alt) {
                        $alt_n = $this->normalize_text($alt);
                        if ($alt_n !== '' && $query_l === $alt_n) $score += 130;
                        if ($alt_n !== '' && preg_match('/\b' . preg_quote($alt_n, '/') . '\b/', $query_l)) $score += 95;
                    }
                }
            }

            $score += ($depth * 10);
            if ($score > $best_score) {
                $best_score = $score;
                $cat['score'] = $score;
                $best = $cat;
            }
        }

        return $best_score >= 170 ? $best : null;
    }

    private function category_slugs_from_ids($category_ids = []) {
        $slugs = [];
        foreach ((array) $category_ids as $category_id) {
            $term = get_term((int) $category_id, 'product_cat');
            if ($term && !is_wp_error($term) && !empty($term->slug)) {
                $slugs[] = (string) $term->slug;
            }
        }
        return array_values(array_unique(array_filter($slugs)));
    }

    private function normalize_search_terms($terms = []) {
        $normalized = [];
        $seen = [];
        foreach ((array) $terms as $term) {
            $term = $this->normalize_text($term);
            if ($term === '' || isset($seen[$term])) continue;
            $seen[$term] = true;
            $normalized[] = $term;
        }
        return $normalized;
    }

    private function singularize_token($token) {
        $token = $this->normalize_text($token);
        if ($token === '' || strlen($token) < 4) return $token;
        if (substr($token, -3) === 'ies') return substr($token, 0, -3) . 'y';
        if (substr($token, -2) === 'es') return substr($token, 0, -2);
        if (substr($token, -1) === 's') return substr($token, 0, -1);
        return $token;
    }

    private function build_semantic_search_terms($query, $synonyms = [], $matched_categories = [], $category_ids = []) {
        $terms = [];
        $query_n = $this->normalize_text($query);
        if ($query_n !== '') $terms[] = $query_n;

        $tokens = $this->extract_tokens($query);
        if ($tokens) {
            $terms[] = implode(' ', $tokens);
            foreach ($tokens as $token) {
                $terms[] = $token;
                $singular = $this->singularize_token($token);
                if ($singular !== '' && $singular !== $token) $terms[] = $singular;
            }
        }

        $top_categories = array_slice((array) $matched_categories, 0, 4);
        foreach ($top_categories as $cat) {
            $name = $this->normalize_text($cat['name'] ?? '');
            $slug = $this->normalize_text(str_replace('-', ' ', $cat['slug'] ?? ''));
            $path = $this->normalize_text($cat['path_string'] ?? '');
            if ($name !== '') $terms[] = $name;
            if ($slug !== '' && $slug !== $name) $terms[] = $slug;
            if ($path !== '' && $path !== $name && $path !== $slug) $terms[] = $path;
        }

        foreach ((array) $category_ids as $category_id) {
            $term = get_term((int) $category_id, 'product_cat');
            if ($term && !is_wp_error($term)) {
                $name = $this->normalize_text($term->name ?? '');
                $slug = $this->normalize_text(str_replace('-', ' ', $term->slug ?? ''));
                if ($name !== '') $terms[] = $name;
                if ($slug !== '' && $slug !== $name) $terms[] = $slug;
            }
        }

        foreach ((array) $synonyms as $label => $list) {
            $label_n = $this->normalize_text($label);
            if ($label_n === '') continue;
            $matched_label = ($query_n !== '' && (strpos($query_n, $label_n) !== false || $label_n === $query_n));
            $matched_alt = false;
            $alt_terms = [];
            foreach ((array) $list as $alt) {
                $alt_n = $this->normalize_text($alt);
                if ($alt_n === '') continue;
                $alt_terms[] = $alt_n;
                if ($query_n !== '' && (strpos($query_n, $alt_n) !== false || $alt_n === $query_n)) {
                    $matched_alt = true;
                }
            }
            if ($matched_label || $matched_alt) {
                $terms[] = $label_n;
                foreach (array_slice($alt_terms, 0, 3) as $alt_n) {
                    $terms[] = $alt_n;
                }
            }
        }

        $terms = $this->normalize_search_terms($terms);
        return array_slice($terms, 0, 8);
    }

    public function search_products_semantic($query, $args = [], $synonyms = [], $matched_categories = []) {
        if (!function_exists('wc_get_products')) return [];

        $limit = isset($args['limit']) ? max(8, absint($args['limit'])) : 12;
        $candidate_limit = isset($args['candidate_limit']) ? max($limit, absint($args['candidate_limit'])) : max(18, $limit * 3);
        $category_ids = !empty($args['category_ids']) && is_array($args['category_ids']) ? array_values(array_unique(array_map('absint', $args['category_ids']))) : [];

        $variants = $this->build_semantic_search_terms($query, $synonyms, $matched_categories, $category_ids);
        $batches = [];
        $primary_query = $this->normalize_text($query);
        if ($primary_query !== '') {
            $batches[] = ['query' => $primary_query, 'category_ids' => $category_ids];
        }
        if ($category_ids) {
            $batches[] = ['query' => '', 'category_ids' => $category_ids];
        }
        foreach ($variants as $variant) {
            $batches[] = ['query' => $variant, 'category_ids' => $category_ids];
        }

        $results = [];
        $seen = [];
        foreach ($batches as $batch) {
            $batch_limit = min(max($candidate_limit, $limit), 48);
            $items = $this->search_products($batch['query'], [
                'limit' => $batch_limit,
                'category_ids' => $batch['category_ids'],
            ]);
            foreach ((array) $items as $product) {
                if (!$product) continue;
                $product_id = (int) $product->get_id();
                if ($product_id <= 0 || isset($seen[$product_id])) continue;
                $seen[$product_id] = true;
                $results[] = $product;
                if (count($results) >= $candidate_limit) {
                    break 2;
                }
            }
        }

        return $results;
    }

    public function search_products($query, $args = []) {
        if (!function_exists('wc_get_products')) return [];
        $params = [
            'status' => 'publish',
            'limit' => isset($args['limit']) ? max(8, absint($args['limit'])) : 12,
            'return' => 'objects',
            'stock_status' => ['instock', 'onbackorder'],
        ];
        if ($query !== '') $params['s'] = $query;
        if (!empty($args['category_ids'])) {
            $category_slugs = $this->category_slugs_from_ids($args['category_ids']);
            if (!empty($category_slugs)) {
                $params['category'] = $category_slugs;
            }
        }
        return wc_get_products($params);
    }

    public function product_payload($product) {
        if (!$product) return null;
        $cats = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);

        $price_display = '';
        $price_range_text = '';

        if ($product->is_type('variable')) {
            $min = (float) $product->get_variation_price('min', true);
            $max = (float) $product->get_variation_price('max', true);
            if ($min > 0 && $max > 0) {
                $min_text = wp_strip_all_tags(wc_price($min));
                $max_text = wp_strip_all_tags(wc_price($max));
                $price_display = $min_text . ' – ' . $max_text;
                $price_range_text = 'Price range: ' . $min_text . ' through ' . $max_text;
            }
        }

        if ($price_display === '') {
            $display_price = (float) wc_get_price_to_display($product);
            if ($display_price > 0) {
                $price_display = wp_strip_all_tags(wc_price($display_price));
            } else {
                $price_display = wp_strip_all_tags($product->get_price_html());
            }
        }

        return [
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'price_html' => $price_display,
            'price_range_text' => $price_range_text,
            'price' => $product->get_price(),
            'permalink' => $product->get_permalink(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
            'short_description' => wp_strip_all_tags($product->get_short_description()),
            'stock_status' => $product->get_stock_status(),
            'categories' => is_array($cats) ? $cats : [],
            'category_url' => (!empty($cats) && !empty($cats[0]['url'])) ? $cats[0]['url'] : '',
            'deepest_category_url' => (!empty($cats) ? (function($items){ usort($items, function($a,$b){ return (int)($b['depth'] ?? 0) - (int)($a['depth'] ?? 0); }); return (string) ($items[0]['url'] ?? ''); })($cats) : ''),
            'on_sale' => $product->is_on_sale(),
        ];
    }

    public function score_products($products, $query, $matched_categories = []) {
        $query_l = $this->normalize_text($query);
        $tokens = $this->extract_tokens($query);
        $matched_cat_ids = wp_list_pluck($matched_categories, 'id');
        $matched_cat_names = array_map([$this, 'normalize_text'], wp_list_pluck($matched_categories, 'name'));
        $scored = [];
        foreach ($products as $product) {
            $score = 0;
            $name = $this->normalize_text($product->get_name());
            $desc = $this->normalize_text(wp_strip_all_tags($product->get_short_description()));
            $sku = $this->normalize_text($product->get_sku());
            $cats = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'all']);
            $product_cat_names = [];
            $product_cat_ids = [];
            foreach ((array) $cats as $cat) {
                $product_cat_names[] = $this->normalize_text($cat->name ?? '');
                $product_cat_names[] = $this->normalize_text(str_replace('-', ' ', $cat->slug ?? ''));
                $product_cat_ids[] = (int) ($cat->term_id ?? 0);
            }
            $haystack = trim($name . ' ' . $desc . ' ' . $sku . ' ' . implode(' ', $product_cat_names));

            if ($query_l !== '' && strpos($name, $query_l) !== false) $score += 120;
            foreach ($tokens as $token) {
                if (strpos($name, $token) !== false) $score += 25;
                if (strpos($desc, $token) !== false) $score += 10;
                if (strpos($sku, $token) !== false) $score += 8;
                foreach ($product_cat_names as $cat_name) {
                    if ($cat_name !== '' && strpos($cat_name, $token) !== false) $score += 35;
                }
            }

            foreach ($matched_cat_ids as $cat_id) {
                if (in_array((int) $cat_id, $product_cat_ids, true)) $score += 90;
            }
            foreach ($matched_cat_names as $matched_name) {
                if ($matched_name !== '' && in_array($matched_name, $product_cat_names, true)) $score += 45;
            }

            $matched_token_count = 0;
            foreach ($tokens as $token) {
                if ($token !== '' && strpos($haystack, $token) !== false) $matched_token_count++;
            }
            if ($matched_token_count === 0 && !empty($tokens)) $score -= 120;
            elseif ($matched_token_count === 1 && count($tokens) >= 2) $score -= 25;
            else $score += $matched_token_count * 12;

            if ($product->is_in_stock()) $score += 8;
            if ($product->is_on_sale()) $score += 4;
            if ($product->is_featured()) $score += 3;

            if ($score > 0) $scored[] = ['score' => $score, 'product' => $product];
        }
        usort($scored, function($a, $b){ return $b['score'] <=> $a['score']; });
        return $scored;
    }
}
