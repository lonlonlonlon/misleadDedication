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

squares = [pygame.Rect(10, 10, 490, 490)]