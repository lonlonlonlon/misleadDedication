import math
import os
import random
import pygame
import sys

pygame.init()

clock = pygame.time.Clock()

FPS = 15  # How many times the screen will update per second

width = 1000
height = 1414
border = 5

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

for i in len(rects):
    pygame.draw.line()