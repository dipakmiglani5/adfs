#!/usr/bin/env python3
import subprocess
import os
from os import system, name
from time import sleep
#print("hello")
import sys
name_of_file= "uploads/" + sys.argv[1]
#name_of_file= "uploads/26.jpg"

def clear():
    if name == 'nt':
        _ = system('cls')
    else:
        _ = system('clear')

os.environ["LD_LIBRARY_PATH"] = "/usr/local/cuda/lib64:$LD_LIBRARY_PATH"

result  = subprocess.run(["./darknet", "detect", "cfg/yolov4.cfg", "yolov4.weights", name_of_file, 
"-thresh",  "0.25",  "2>nul"], stdout=subprocess.PIPE, text=True)

clear()

test=result.stdout
#print(test)
j=test.find("milli-seconds",0,len(test))
#print(test[j+17])
text = test[j+15:]
n = len(text)
words = []
#print('hey')
for i in range(n):
    if text[i]==":":
        j = i-1
        st = ""
        while text[j]!="\n" and j!=-1:
            st = text[j] + st
            j = j - 1
        words.append(st)
word_count = {}
for i in range(len(words)):
    if words[i] in word_count:
        word_count[words[i]] += 1
    else :
        word_count[words[i]] = 1

res = ' '.join(key + str(val) for key, val in word_count.items())
res.pop(0)
print(res)

 
