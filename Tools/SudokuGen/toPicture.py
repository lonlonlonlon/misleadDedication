import math
import os
import random
import pygame
import sys

pygame.init()

clock = pygame.time.Clock()

FPS = 15  # How many times the screen will update per second

width = 10000
height = 14140
border = (width/100)*3
lineStrength = (width/1000)*2

hPart = height/3
wPart = width/2

left1 = pygame.Rect((wPart*0)+border, (hPart*0)+border, (wPart*1)-border, (hPart*1)-border)
right1 = pygame.Rect((wPart*1)+border, (hPart*0)+border, (wPart*2)-border, (hPart*1)-border)

left2 = pygame.Rect((wPart*0)+border, (hPart*1)+border, (wPart*1)-border, (hPart*2)-border)
right2 = pygame.Rect((wPart*1)+border, (hPart*1)+border, (wPart*2)-border, (hPart*2)-border)

left3 = pygame.Rect((wPart*0)+border, (hPart*2)+border, (wPart*1)-border, (hPart*3)-border)
right3 = pygame.Rect((wPart*1)+border, (hPart*2)+border, (wPart*2)-border, (hPart*3)-border)

rects = [left1, left2, left3, right1, right2, right3]

pic = pygame.Surface((width, height))
pic.fill((255,255,255))

for i in range(len(rects)):
    print(i)
    rect = rects[i]
    (x, y, xx, yy) = rect
    pygame.draw.line(pic, (0, 0, 0), (x, y), (x, yy), lineStrength)
    pygame.draw.line(pic, (0, 0, 0), (x, y), (xx, y), lineStrength)
    pygame.draw.line(pic, (0, 0, 0), (xx, yy), (x, yy), lineStrength)
    pygame.draw.line(pic, (0, 0, 0), (xx, yy), (xx, y), lineStrength)

    pygame.draw.line(pic, (0, 0, 0), (x/3, y), (x/3, yy), lineStrength)
    # TODO: eigene surfaces für jedes sudoku, nacher auf pic blitten, sonst Koordinaten AA
    pygame.draw.line(pic, (0, 0, 0), ((x/3)*2, y), ((x/3)*2, yy), lineStrength)

pygame.image.save(pic, 'tmp.png', 'png')