#!/usr/bin/python3
# -*- coding: utf-8 -*-

import xml.etree.ElementTree as ET
import unidecode

from datetime import datetime, timedelta

ini_time = datetime.now()

# Display a title bar.
# print("  _____________________________________________________")
# print(" /               _   _    _       _                 _  \\")
# print("|     /\        | | | |  | |     | |               | | |")
# print("|    /  \   _ __| |_| |  | |_ __ | | ___   __ _  __| | |")
# print("|   / /\ \ | '__| __| |  | | '_ \| |/ _ \ / _` |/ _` | |")
# print("|  / ____ \| |  | |_| |__| | |_) | | (_) | (_| | (_| | |")
# print("| /_/    \_\_|   \__|\____/| .__/|_|\___/ \__,_|\__,_| |")
# print("|                          | |                         |")
# print("|                          |_|                         |")
# print("|                                                      |")
# print("|           Обновления каталогов АртИмперии            |")
# print("\\______________________________________________________/")


tree = ET.parse("production.xml")
# root = tree.getroot()

# for child in root:
# # 	print(child.tag, child.attrib)

for group in tree.findall('Группы/Группа'):
    print('Ид: %s' % (group.find('Ид').text))
    print('ИдРодителя: %s' % (group.find('ИдРодителя').text))
    print('Наименование: %s' % (group.find('Наименование').text))
    print('-----')

end_time = datetime.now()
print('========')
print('Программа выполнена за %s' % (end_time - ini_time))
